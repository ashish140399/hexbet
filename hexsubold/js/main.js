
//referral handling
if (window.location.href.includes("r=0x")) { //new ref
  referralAddress = getAllUrlParams(window.location.href).r;
  document.cookie = "r=" + referralAddress + "; expires=Monday, 01 Jan 2120 12:00:00 UTC; path=/";
  console.log("new ref cookie: " + referralAddress);
} else { //get cookie
  var cookie = getCookie("r");
  if (cookie != "" && cookie.includes("0x")) { //cookie found
    referralAddress = cookie;
    console.log("cookie ref: " + referralAddress);
  } else { //cookie nor url ref found 
    referralAddress = "0x0000000000000000000000000000000000000000";
    console.log("ref: " + referralAddress);
  }
}

//mobile setup
if (isDeviceMobile()) {

}

setTimeout(async function(){
  getAvailableDividends();
  await getUserFreezings();
  UpdateData();
}, 3000);
setInterval(function(){
  UpdateData();
},15000);//15 secs

async function AddToMetamask(){
  errorMessage("This feature is coming soon.<br/>You can add HXY manually using the contract address.");
  return;
}

async function Approve() {
 // var hexInput = document.getElementById("hexInput");
 // if (hexInput.value == null || hexInput.value <= 0 || hexInput.value == undefined) {
 //   console.log("check values");
 //   errorMessage("HEX amount needed for approve, check the form and try again.");
 //   return;
 // }
 // if (hexInput.value.includes(".")) {
 //   errorMessage("Invalid input, HEX input does not accept decimals");
 //   return;
 // }
 // var hex = hexInput.value;
 // var approvedHex = document.getElementById("approvedHex");
 // var hearts = parseInt(web3.utils.toBN(hexInput.value));
 // hearts *= 10 ** decimals;
  var value = "99999999999999999999999999999999999999999999999999999999999999999999"; //max approve - approve once for all
  hexContract.methods.approve(HEX_EXCHANGE, web3.utils.toHex(value)).send({
      from: activeAccount
    })
    .on('receipt', function (receipt) {
      successMessage("Successfully approved HEX");
      console.log(receipt);
    })
    .on('error', function () {
      console.error;
      errorMessage("Approve failed, please try again...");
    }); // If there's an out of gas error the second parameter is the receipt 
  usdcContract.methods.approve(USDC_EXCHANGE, web3.utils.toHex(value)).send({
      from: activeAccount
    })
    .on('receipt', function (receipt) {
      successMessage("Successfully approved USDC");
      console.log(receipt);
    })
    .on('error', function () {
      console.error;
      errorMessage("Approve failed, please try again...");
    }); // If there's an out of gas error the second parameter is the receipt 
}

function SetActiveInput(elem) {
  document.getElementById("activeInput").id = "";
  elem.id = "activeInput";
}

function SelectTransform(elem) {
   var coin = elem.options[elem.selectedIndex].text;
   console.log(coin);
   document.getElementById("transformCoin").innerHTML = coin;
   document.getElementById("transformCoin2").innerHTML = coin;
   TransformChange(document.getElementById("transformInput"), coin);
}

async function TransformChange(elem, coin) {
  var hxyValue = 0;
  var inputValue = elem.value;
  if(inputValue == "undefined" || isNaN(inputValue) || inputValue == ""){
    inputValue = 0;
  }
  console.log(inputValue);
  if(coin == "HEX"){
    hxyValue = inputValue / (1000 * 2);
  }
  else if(coin == "USDC"){
  //
  }
  else{
  //
  }
  document.getElementById("hxyConversion").innerHTML = hxyValue;
}

function getUSDCPrice() {
    return uniswapUsdc.methods.getEthToTokenInputPrice((10 ** 18).toString()).call();
}

function getHexPrice() {
    return uniswapContract.methods.getEthToTokenInputPrice((10 ** 18).toString()).call();
}

function getHxyRate() {
   return hxyContract.methods.getCurrentHxyRate().call();
}

function getAvailableDividends() {
    dividendsContract.methods.getAvailableDividends(activeAccount).call().then((result) => {
      result.map((dividend, index) => {
        if(dividendsKeys[index] == "ETH"){
          document.getElementById("ethDivs").value = web3.utils.fromWei(dividend);
        }
        if(dividendsKeys[index] == "HEX"){
          document.getElementById("hexDivs").value = (dividend / 10 ** 8);
        }
        if(dividendsKeys[index] == "HXY"){
          document.getElementById("hxyDivs").value = (dividend / 10 ** 8);
        }
        if(dividendsKeys[index] == "USDC"){
          document.getElementById("usdcDivs").value = (dividend / 10 ** 6);
        }
      });
    }).catch((err) => {
      return null;
    });
  }

function ClaimDividends() {
    dividendsContract.methods.claimDividends().send({
      from: activeAccount
    }).on('receipt', function (receipt) {
      successMessage("Successfully claimed dividends!");
      console.log(receipt);
    })
    .on('error', function () {
      console.error;
      errorMessage("Claim failed, please try again...");
    }); 
  }

function Transform(){
    var amount = document.getElementById("transformInput").value;
    var coin = document.getElementById("transformCoin").innerHTML;
    if(coin == "HEX"){
      amount *= 10 ** 8;
      sendHEX(amount);
    }
    else if(coin == "USDC"){
      amount *= 10 ** 6;
      sendUSDC(amount);
    }
    else{

      sendETH(amount);
    }
  }


  async function sendETH(amount) {
    var balance = await web3.eth.getBalance(activeAccount);
    console.log(balance);
    console.log(amount);
    if(amount > web3.utils.fromWei(balance)){
      errorMessage("Insufficient ETH");
      return;
    }
    amount = web3.utils.toWei(amount);
    web3.eth.sendTransaction({
      value: amount,
      from: activeAccount,
      to: ethExchange.options.address
    }).on('receipt', function (receipt) {
      successMessage('ETH successfully transformed to HXY');
      console.log(receipt);
      setTimeout(function(){
        Populate();
      },5000)
    })
    .on('error', function (error){
      errorMessage('Something went wrong, try again');
      console.log(error);
    });
  }

  async function sendHEX(amount) {
    var balance = await hexContract.methods.balanceOf(activeAccount).call();
    console.log(balance);
    if(amount > balance){
      errorMessage("Insufficient HEX");
      return;
    }
    var allow = await hexContract.methods.allowance(activeAccount, HEX_EXCHANGE).call();
    console.log(allow);
    if(amount > allow){
      errorMessage('You must approve Metamask');
      return;
    }
    hexExchange.methods.exchangeHex(amount).send({
      from: activeAccount
    }).on('receipt', function (receipt) {
      successMessage('HEX successfully transformed to HXY');
      console.log(receipt);
      setTimeout(function(){
        Populate();
      },5000)
    })
    .on('error', function (error){
      errorMessage('Something went wrong, try again');
      console.log(error);
    });
  }


  async function sendUSDC(amount) {
    var balance = await usdcContract.methods.balanceOf(activeAccount).call();
    console.log(balance);
    if(amount > balance){
      errorMessage("Insufficient USDC");
      return;
    }
    var allow = await usdcContract.methods.allowance(activeAccount, USDC_EXCHANGE).call();
    console.log(allow);
    if(allow < amount){
      errorMessage('You must approve Metamask');
      return;
    }
    usdcExchange.methods.exchangeUsdc(amount).send({
      from: activeAccount
    }).on('receipt', function (receipt) {
      successMessage('USDC successfully transformed to HXY');
      console.log(receipt);
      setTimeout(function(){
        Populate();
      },5000)
    })
    .on('error', function (error){
      errorMessage('Something went wrong, try again');
      console.log(error);
    });
  }

async function FreezeTokens() {
  if (!sendok) {
    errorMessage("Cannot send tx, please check connection");
    return;
  }
  if (typeof web3 !== "undefined") {
    var value = document.getElementById("freezeAmount").value;
    var balance = await hxyContract.methods.balanceOf(activeAccount).call();
    if (value == null || value <= 0 || value == "") {
      errorMessage("Value must be greater than 0");
      return;
    }
    var hxy = value;
    var _hxy = hxy * 10 ** 8;
    if (balance < _hxy) {
      errorMessage("Insufficient HXY token balance");
      return;
    }
    hxyContract.methods.freezeHxy(_hxy).send({
      from: activeAccount
    }).then(function () {
        successMessage("HXY frozen successfully!");
        setTimeout(function(){
          Populate();
        },5000)
    });
  }
}

async function UnfreezeTokens() {

}

var frozenTokens;
async function getUserFreezings() {
  frozenTokens = 0;
  const account = activeAccount;
  getBlock().then(async function(block) {
    const timeRange = new Date().getTime() - block.timestamp * 1000;
    var result = await hxyContract.methods.getUserFreezings(account).call();
      const allFreezingsPromises = result.map((freezId) => {
        hxyContract.methods.getFreezingById(freezId).call().then((freezing) => {
          freezing.id = freezId;
          freezing.endDateTime = (+freezing.startDate + freezing.freezeDays * oneDaySeconds) * 1000 + timeRange;
          hxyContract.methods.getCurrentInterestAmount(account, freezing.startDate).call().then((interest) => {
            freezing.interest = interest;
            console.log(freezing);
            frozenTokens += parseInt(freezing.freezeAmount);
          });
        });
      });
      GetBalance();
       Promise.all(allFreezingsPromises).then((freezings) => {
        freezings.filter((freezing) => {
          return +freezing.id > 0;
        }).sort((a, b) => {
          return +a.endDateTime > +b.endDateTime ? 1 : -1;
        });
      });
  
  });
}

function getBlock() {
  return web3.eth.getBlockNumber();
}


async function UpdateData(){
  var totalSupply = await hxyContract.methods.totalSupply().call();
  var maxSupply = await hxyContract.methods.cap().call();
  var frozenSupply = await hxyContract.methods.getTotalFrozen().call();
  var lockedSupply  = await hxyContract.methods.getLockedSupply().call();
  totalSupply /= 10 ** 8;
  maxSupply /= 10 ** 8;
  frozenSupply /= 10 ** 8;
  lockedSupply /= 10 ** 8;
  document.getElementById("totalSupply").innerHTML =  totalSupply;
  document.getElementById("maxSupply").innerHTML =  maxSupply;
  document.getElementById("frozenSupply").innerHTML =  frozenSupply;
  document.getElementById("lockedSupply").innerHTML =  lockedSupply;
  document.getElementById("circulatingSupply").innerHTML = (totalSupply - (lockedSupply + frozenSupply));
}

async function GetBalance() {
	var hex = await hexContract.methods.balanceOf(activeAccount).call();
	hex /= 10 ** 8;
	var hxy = await hxyContract.methods.balanceOf(activeAccount).call();
	hxy /= 10 ** 8;
	var usdc = await usdcContract.methods.balanceOf(activeAccount).call();
	usdc /= 10 ** 6;
	var eth = await web3.eth.getBalance(activeAccount);
	eth /= 10 ** 18;
	document.getElementById("hxyBalance").innerHTML = toFixedMax(hxy, 8);
	document.getElementById("hxyAvailable").innerHTML = toFixedMax(hxy, 8) - (frozenTokens / 10 ** 8);
	document.getElementById("hexBalance").innerHTML = toFixedMax(hex, 4);
	document.getElementById("usdcBalance").innerHTML = toFixedMax(usdc, 2);
	document.getElementById("ethBalance").innerHTML = toFixedMax(eth, 16);
}

async function UpdateTables() {

}

function DonateEth() {
  if (typeof web3 !== "undefined") {
    Populate();
    //donate
    const input = document.getElementById('ethDonate');
    if (input.value <= 0) {
      return;
    } else {
      let donateWei = new window.web3.utils.BN(
        window.web3.utils.toWei(input.value, "ether")
      );
      window.web3.eth.net.getId().then(netId => {
        return window.web3.eth.getAccounts().then(accounts => {
          return window.web3.eth
            .sendTransaction({
              from: accounts[0],
              to: donationAddress,
              value: donateWei
            })
            .catch(e => {
              errorMessage('Something went wrong, make sure your wallet is enabled and logged in.');
            });
        });
      });
    }
  }
}

function DonateHex() {
  if (typeof web3 !== "undefined") {
    Populate();
    //donate
    const input = document.getElementById('hexDonate');
    if (input.value <= 0) {
      return;
    } else {
      let donateTokens = input.value;
      let amount = web3.utils.toBN(donateTokens);

      window.web3.eth.net.getId().then(netId => {
        return window.web3.eth.getAccounts().then(accounts => {
          // calculate ERC20 token amount
          let value = amount * 10 ** decimals;
          // call transfer function
          return hexContract.methods.transfer(donationAddress, value).send({
              from: accounts[0]
            })
            .on('transactionHash', function (hash) {
              successMessage('Thank you! You can see your donation on https://etherscan.io/tx/' + hash);
            });
        }).catch(e => {
          errorMessage('Something went wrong, make sure your wallet is enabled and logged in.');
        });
      });
    }
  }
}

function CalcTimeElapsed(timestamp) {
  var seconds = (Date.now() / 1000) - parseInt(timestamp);
  var minutes = seconds / 60;
  var hours = minutes / 60;
  var days = hours / 24;
  var weeks = days / 7;
  var years = weeks / 52;
  var months = years * 12;
  if (minutes < 1) {
    return seconds.toFixed().toString() + "s ago";
  } else if (hours < 1) {
    return minutes.toFixed().toString() + "m ago";
  } else if (days < 1) {
    return hours.toFixed().toString() + "h ago";
  } else if (weeks < 1) {
    return days.toFixed().toString() + "d ago";
  } else if (months < 1) {
    return weeks.toFixed().toString() + "w ago";
  } else if (years < 1) {
    return months.toFixed().toString() + "m ago";
  } else {
    return years.toFixed().toString() + "y ago";
  }
}

/*----------HELPER FUNCTIONS------------ */

function getCookie(cname) {
  var name = cname + "=";
  var ca = document.cookie.split(';');
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function getAllUrlParams(url) {

  // get query string from url (optional) or window
  var queryString = url ? url.split('?')[1] : window.location.search.slice(1);

  // we'll store the parameters here
  var obj = {};

  // if query string exists
  if (queryString) {

    // stuff after # is not part of query string, so get rid of it
    queryString = queryString.split('#')[0];

    // split our query string into its component parts
    var arr = queryString.split('&');

    for (var i = 0; i < arr.length; i++) {
      // separate the keys and the values
      var a = arr[i].split('=');

      // set parameter name and value (use 'true' if empty)
      var paramName = a[0];
      var paramValue = typeof (a[1]) === 'undefined' ? true : a[1];

      // (optional) keep case consistent
      paramName = paramName.toLowerCase();
      if (typeof paramValue === 'string') paramValue = paramValue.toLowerCase();

      // if the paramName ends with square brackets, e.g. colors[] or colors[2]
      if (paramName.match(/\[(\d+)?\]$/)) {

        // create key if it doesn't exist
        var key = paramName.replace(/\[(\d+)?\]/, '');
        if (!obj[key]) obj[key] = [];

        // if it's an indexed array e.g. colors[2]
        if (paramName.match(/\[\d+\]$/)) {
          // get the index value and add the entry at the appropriate position
          var index = /\[(\d+)\]/.exec(paramName)[1];
          obj[key][index] = paramValue;
        } else {
          // otherwise add the value to the end of the array
          obj[key].push(paramValue);
        }
      } else {
        // we're dealing with a string
        if (!obj[paramName]) {
          // if it doesn't exist, create property
          obj[paramName] = paramValue;
        } else if (obj[paramName] && typeof obj[paramName] === 'string') {
          // if property does exist and it's a string, convert it to an array
          obj[paramName] = [obj[paramName]];
          obj[paramName].push(paramValue);
        } else {
          // otherwise add the property
          obj[paramName].push(paramValue);
        }
      }
    }
  }

  return obj;
}

function numStringToBytes32(num) {
  var bn = new web3.utils.BN(num).toTwos(256);
  return padToBytes32(bn.toString(16));
}

function bytes32ToNumString(bytes32str) {
  bytes32str = bytes32str.replace(/^0x/, '');
  var bn = new web3.utils.BN(bytes32str, 16).fromTwos(256);
  return bn.toString();
}

function bytes32ToInt(bytes32str) {
  bytes32str = bytes32str.replace(/^0x/, '');
  var bn = new web3.utils.BN(bytes32str, 16).fromTwos(256);
  return bn;
}

function padToBytes32(n) {
  while (n.length < 64) {
    n = "0" + n;
  }
  return "0x" + n;
}

function toFixedMax(value, dp) {
  return +parseFloat(value).toFixed(dp);
}