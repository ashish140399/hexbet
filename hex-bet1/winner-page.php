<?php
include('includes/common_includes.php');
include('includes/slots.php');

$windowID = rand(); // WindowID is used to identify each Window, in case the user opens more than one at a time, and spins in all of them. Sent straight up to the server.
$userID = GameUtils::DemandLoginOnRender();
$userBalance = Users::GetUserBalance($userID);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="css/owl.carousel.css" />
    <link rel="stylesheet" type="text/css" href="css/animate.css" />
    <link rel="stylesheet" type="text/css" href="css/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/dataTables.responsive.css">
    <link rel="stylesheet" type="text/css" href="css/style.css?ver=1.0.31" />
    <link rel="stylesheet" type="text/css" href="css/responsive.css" />
    <link rel="icon" href="images/favicon.png" type="image/gif" sizes="16x16">
    <!-- slot css  -->
    <link type="text/css" rel="stylesheet" href="css/slots.css?ver=1.0.31" />
    <link type="text/css" rel="stylesheet" href="css/template5.css" />
    <title>Home - Hex.Bet Winner</title>
</head>
<body>
       <?php
        // This will render ALL the types of slots that come with the game.
        // leave only the one you want!

        // Default slot
        $gameType = "default"; // Modify on this line which game Type you'd like to show
        
     
    ?>
    <?php
// ---------------------------------------------------------------------------
//
// Must define $userID and $gameType before including this PHP template!
//
// ---------------------------------------------------------------------------

if (!$userID) { throw new Exception('Logged User not defined. You must define $userID before requiring slots_template.php'); }
if (!$gameType) { throw new Exception('Game Type not defined. You must define $gameType before requiring slots_template.php'); }

$gameSettings = Slots::GetGameSettings($gameType, true);
$prizes = PrizesAndReels::ListPrizesForRendering($gameType);
?>


    <!-- Chat Box -->
    <button class="btn-main btn-chat" id="btn-ochat"><i class="fa fa-comment"></i></button>
    <button class="btn-main btn-close-chat"><i class="fa fa-times"></i></button>
    <div class="chat-wrapper sidenav" id="chat-side">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <div class="chat-block">
            <div class="chat-top">
                <h4>Chat</h4>
                <select>
                    <option>Eng</option>
                    <option>Other</option>
                </select>
            </div>
            <div class="chat-message">
                <div class="chat-left">
                    <div class="chat-nm">
                        <p>Anatoliji</p>
                        <span>22:45</span>
                    </div>
                    <div class="chat-msg">
                        <p>good, you?</p>
                    </div>
                </div>
                <div class="chat-left">
                     <div class="chat-nm">
                        <p>Anatoliji</p>
                        <span>22:45</span>
                    </div>
                    <div class="chat-msg">
                        <p>good, you?</p>
                    </div>
                </div>
                <div class="chat-left">
                    <div class="chat-nm">
                        <p>Anatoliji</p>
                        <span>22:45</span>
                    </div>
                    <div class="chat-msg">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.Lorem Ipsum is simply</p>
                    </div>
                </div>
                <div class="chat-left">
                    <div class="chat-nm">
                        <p>Anatoliji</p>
                        <span>22:45</span>
                    </div>
                    <div class="chat-msg">
                        <p>good, you?</p>
                    </div>
                </div>
                <div class="chat-left">
                    <div class="chat-nm">
                        <p>Anatoliji</p>
                        <span>22:45</span>
                    </div>
                    <div class="chat-msg">
                        <p>good, you? or whats the problem wit</p>
                    </div>
                </div>
                <div class="chat-left">
                    <div class="chat-nm">
                        <p>Anatoliji</p>
                        <span>22:45</span>
                    </div>
                    <div class="chat-msg">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.Lorem Ipsum is simply</p>
                    </div>
                </div>
                <div class="chat-left">
                    <div class="chat-nm">
                        <p>Anatoliji</p>
                        <span>22:45</span>
                    </div>
                    <div class="chat-msg">
                        <p>good, you?</p>
                    </div>
                </div>
            </div>
            <div class="chat-text-box">
                <input type="text" class="form-control" placeholder="TYPE HERE..">
                <a href="#" class="msg-sent"><img src="images/icons/sent-btn.png" alt="icon"></a>
            </div>
        </div>
    </div>
    <!-- End Chat Box -->

    <!-- Header -->
    <nav class="navbar navbar-default inner-nav wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.20s">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html"><img src="images/logo.png" alt="LOGO"></a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav" id="menu">
                    <li><a href="#">Home</a></li>
                    <li class="active"><a href="#">games</a></li>
                    <li><a href="#">how to play</a></li>
                    <li><a href="#">dividends</a></li>
                    <li class="notify-btn">
                        <a href="#"><img src="images/icons/bell-icon.png"> You got a notification</a>
                    </li>
                    <li class="nv-balance">
                        <p>XP Balance</p>
                        <a href="#"> 1.000 <span>XP</span></a>
                    </li>
                    <li class="nv-balance">
                        <p>Balance</p>
                        <a href="#"><img src="images/icons/pairs-icon.png" alt="icons"> 1.000 <small>hex</small></a>
                        <a href="#" class="plus-nv"><img src="images/icons/plus-icon.png" alt="icons"></a>
                    </li>
                    <li class="nv-select">
                        <select class="form-control">
                            <option>User name</option>
                            <option>User name</option>
                            <option>User name</option>
                        </select>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Header -->

    <!-- Banner Hex -->
    <section class="banner-inner winner-banner">
        <div class="container">
            <div class="banner-inner-wrapper">
                <div class="banner-winner slot_machine_outer_container" data-game-settings="<?php echo str_replace('"', "&quot;",json_encode($gameSettings)); ?>">
                    <h1 class="inner-title"><img src="images/winner-text.png" alt="img"></h1>
                    <div class="slot_machine_win_bg">
                        <div class="winner-block">
                         
                                <div class="slot_machine_container">

                                    <div class="slot_machine_reel_container">
                                         <div class="cropreel_overlay">
                                        <div class="slot_machine_reel slot_machine_reel1"></div>
                                        <div class="slot_machine_reel slot_machine_reel2"></div>
                                        <div class="slot_machine_reel slot_machine_reel3"></div>
                                       </div>
                                        <div class="reel_overlay"></div>
                                    </div>

                                    <div class="slot_machine_logged_out_message" style="display: none;"><span class="large">Sorry, you have been logged off.</span><br />
                                        <b>No bids</b> have been deducted from this spin, because you're not logged in anymore.
                                        Please <a href="/login">login</a> and try again.
                                    </div>
                                    <div class="slot_machine_failed_request_message" style="display: none;"><span class="large">Sorry, we're unable to display your spin because your connection to our server was lost. </span><br />
                                        Rest assured that your spin was not wasted.
                                        Please check your connection and <a href="#" onclick="window.location.reload();">refresh</a> to try again.
                                    </div>

                                    <div class="slot_machine_controls banner-bet-wrapper banner-bet-wrapper-winner">
                                        <div class="banner-bet-blk">
                                            <div class="banner-bet-label">
                                                <label>Day Wins</label>
                                               <span class="slot_machine_output_day_winnings">0</span>
                                            </div>
                                        </div>
                                        <div class="banner-bet-blk">
                                            <div class="banner-bet-label">
                                                <label>Lifetime Wins</label>
                                                <span class="slot_machine_output_lifetime_winnings"><?php echo (float) Users::GetUserField($userID, 'slots_lifetime_winnings'); ?></span>
                                            </div>
                                        </div>
                                        <div class="banner-bet-blk">
                                            <div class="banner-bet-label">
                                                <label>Last Wins</label>
                                               <span class="slot_machine_output_last_win"></span>
                                            </div>
                                        </div>
                                        <div class="banner-bet-blk">
                                            <div class="banner-bet-label">
                                                <label>Credit Balance</label>
                                               <span class="slot_machine_output_balance"></span>
                                            </div>
                                        </div>
                                        <div class="banner-bet-blk">
                                            <div class="banner-bet-label">
                                                <label>Bet</label>
                                                <span class="slot_machine_output_bet"></span>
                                                <div class="slot_machine_bet_increase_button"></div>
                                                <div class="slot_machine_bet_decrease_button"></div>
                                            </div>
                                        </div>
                                        <div class="banner-bet-btn">
                                            <button class="btn-main slot_machine_spin_button btn-main-spin">Spin</button>
                                            <!-- <div class="pairs-process">
                                                <div class="pairs-process-blk">
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:40%"></div>
                                                    </div>
                                                </div>
                                            </div> -->
                                        </div>
                                        
                                                
                                        
                                       
                                        
                                    </div>

                                    
                                </div>
                         
                        </div>
                        <div class="chart-list-cl">
                            <ul>
                                <li><p><img src="images/icons/box.png" alt="icons"> Win <strong>Chart</strong></p></li>
                            </ul>
                           <div class="slot_machine_prizes_list">
                                <?php
                                    foreach ($prizes as $prize) { ?>
                                        <div class="slot_machine_prize_row slot_machine_prize_row_<?php echo $prize['id']; ?>">
                                            <div class="slot_machine_prize_reel_sprites">
                                                <div class="slot_machine_prize_reel1 slot_machine_prize_reel_icon <?php echo $prize['reel1_classname']; ?>"></div>
                                                <div class="slot_machine_prize_reel2 slot_machine_prize_reel_icon <?php echo $prize['reel2_classname']; ?>"></div>
                                                <div class="slot_machine_prize_reel3 slot_machine_prize_reel_icon <?php echo $prize['reel3_classname']; ?>"></div>
                                                <div class="clearer"></div>
                                            </div>
                                            <span class="slot_machine_prize_payout" data-basePayout="<?php echo $prize['payout_winnings']; ?>"><?php echo (float) $prize['payout_winnings']; ?></span>
                                            <div class="clearer"></div>
                                        </div>
                                <?php } ?>
                            </div>

                        </div>
                    </div>
                    <style type="text/css">
                        #sm, .lever button {box-shadow: 0 3px 9px rgba(0,0,0,.25)}
                        .group, .reel, .lever {display: inline-block;}

                        .group {
                            border-radius: 30px;
                            left: 50%;
                            position: relative;
                            top: 50%;
                            transform: translate(-50%, -50%);
                            width: 60%
                        }

                        .reel {
                            text-align:center;
                            width: 100%;
                            height: 100px;
                            overflow: hidden;
                            border-radius: 10px;
                        }
                        .reel div {
                          position: relative;
                          top: -100px;
                        }
                        .reel p {
                          font-weight: bold;
                          height: 100px;
                          margin: 0;
                        }
                        .btn-main-spin:focus:before{
                            top: 10px;
                            left: 5px;
                        }
                    </style>
                   <!--  <div class="banner-bet-wrapper banner-bet-wrapper-winner">
                        <div class="banner-bet-blk">
                            <div class="banner-bet-label">
                                <label>Bet</label>
                                <input type="text" class="form-control" placeholder="1000">
                                <img src="images/icons/pairs-icon.png" alt="icons" class="btn-fr-icon">
                                <span class="fr-text">hex</span>
                            </div>
                        </div>
                        <div class="banner-bet-blk">
                            <div class="banner-bet-label">
                                <label>Bet</label>
                                <input type="text" class="form-control" placeholder="1000">
                                <img src="images/icons/pairs-icon.png" alt="icons" class="btn-fr-icon">
                                <span class="fr-text">hex</span>
                            </div>
                        </div>
                        <div class="banner-bet-blk">
                            <div class="banner-bet-label">
                                <label>Bet</label>
                                <input type="number" class="form-control" placeholder="1000">
                                <img src="images/icons/pairs-icon.png" alt="icons" class="btn-fr-icon">
                                <span class="fr-text">hex</span>
                            </div>
                        </div>
                        <div class="banner-bet-btn">
                            <button class="btn-main msg btn-main-spin">Spin</button>
                            <div class="pairs-process">
                                <div class="pairs-process-blk">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:40%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Hex -->

    <!-- Overview -->
    <section class="overview wow fadeIn" data-wow-duration="1s" data-wow-delay="0.20s">
        <div class="container">
            <h4 class="sm-title">Overview</h4>
            <div class="overview-wrapper">
                <div class="hxnet-table">
                    <table class="table dt-responsive">
                        <thead>
                            <tr>
                                <th>Indicator</th>
                                <th>Rate</th>
                                <th>Indicator</th>
                                <th>Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><p>Total Supply</p></td>
                                <td><p><img src="images/icons/pairs-icon.png" alt="icon"> 1.000 <span>hex</span></p></td>
                                <td><p>Collected Today</p></td>
                                <td><p><img src="images/icons/pairs-icon.png" alt="icon"> 1.000 <span>hex</span></p></td>
                            </tr>
                            <tr>
                                <td><p>Max Supply</p></td>
                                <td><p><img src="images/icons/pairs-icon.png" alt="icon"> 1.000 <span>hex</span></p></td>
                                <td><p>Collected Today</p></td>
                                <td><p><img src="images/icons/pairs-icon.png" alt="icon"> 1.000 <span>hex</span></p></td>
                            </tr>
                            <tr>
                                <td><p>Circulating Supply</p></td>
                                <td><p><img src="images/icons/pairs-icon.png" alt="icon"> 1.000 <span>hex</span></p></td>
                                <td><p>Collected Today</p></td>
                                <td><p><img src="images/icons/pairs-icon.png" alt="icon"> 1.000 <span>hex</span></p></td>
                            </tr>
                            <tr>
                                <td><p>Total Locked Supply</p></td>
                                <td><p><img src="images/icons/pairs-icon.png" alt="icon"> 1.000 <span>hex</span></p></td>
                                <td><p>Collected Today</p></td>
                                <td><p><img src="images/icons/pairs-icon.png" alt="icon"> 1.000 <span>hex</span></p></td>
                            </tr>
                            <tr>
                                <td><p>Founder Locked Supply</p></td>
                                <td><p><img src="images/icons/pairs-icon.png" alt="icon"> 1.000 <span>hex</span></p></td>
                                <td><p>Collected Today</p></td>
                                <td><p><img src="images/icons/pairs-icon.png" alt="icon"> 1.000 <span>hex</span></p></td>
                            </tr>
                            <tr>
                                <td><p>Total Interest Earned</p></td>
                                <td><p><img src="images/icons/pairs-icon.png" alt="icon"> 1.000 <span>hex</span></p></td>
                                <td><p>Collected Today</p></td>
                                <td><p><img src="images/icons/pairs-icon.png" alt="icon"> 1.000 <span>hex</span></p></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!-- End Overview -->

    <!-- Footer -->
    <footer class="sh-footer wow fadeIn" data-wow-duration="1s" data-wow-delay="0.20s">
        <div class="ft-head-wrapper">
            <div class="container">
                <div class="ft-head-blk">
                    <div class="ft-head-list">
                        <h4>Head Title</h4>
                        <ul>
                            <li><a href="#">how to play</a></li>
                            <li><a href="#">start setie</a></li>
                            <li><a href="#">Regelwork</a></li>
                        </ul>
                    </div>
                    <div class="ft-head-list">
                        <h4>Head Title</h4>
                        <ul>
                            <li><a href="#">how to play</a></li>
                            <li><a href="#">start setie</a></li>
                            <li><a href="#">Regelwork</a></li>
                        </ul>
                    </div>
                    <div class="ft-head-list">
                        <h4>Head Title</h4>
                        <ul>
                            <li><a href="#">how to play</a></li>
                            <li><a href="#">start setie</a></li>
                            <li><a href="#">Regelwork</a></li>
                        </ul>
                    </div>
                    <div class="ft-head-list">
                        <h4>Head Title</h4>
                        <ul>
                            <li><a href="#">how to play</a></li>
                            <li><a href="#">start setie</a></li>
                            <li><a href="#">Regelwork</a></li>
                        </ul>
                    </div>
                    <div class="ft-head-list">
                        <h4>Head Title</h4>
                        <ul>
                            <li><a href="#">how to play</a></li>
                            <li><a href="#">start setie</a></li>
                            <li><a href="#">Regelwork</a></li>
                        </ul>
                    </div>
                    <div class="ft-head-list">
                        <h4>Head Title</h4>
                        <ul>
                            <li><a href="#">how to play</a></li>
                            <li><a href="#">start setie</a></li>
                            <li><a href="#">Regelwork</a></li>
                        </ul>
                    </div>
                    <div class="ft-head-list">
                        <h4>Head Title</h4>
                        <ul>
                            <li><a href="#">how to play</a></li>
                            <li><a href="#">start setie</a></li>
                            <li><a href="#">Regelwork</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="ft-company-list">
            <div class="container">
                <ul>
                    <li><a href="#"><img src="images/shopify.png" alt="img"></a></li>
                    <li><a href="#"><img src="images/shopify.png" alt="img"></a></li>
                    <li><a href="#"><img src="images/shopify.png" alt="img"></a></li>
                    <li><a href="#"><img src="images/shopify.png" alt="img"></a></li>
                    <li><a href="#"><img src="images/shopify.png" alt="img"></a></li>
                    <li><a href="#"><img src="images/shopify.png" alt="img"></a></li>
                    <li><a href="#"><img src="images/shopify.png" alt="img"></a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bt-wrapper">
                    <div class="footer-logo">
                        <a href="#"><img src="images/logo.png" alt="img"></a>
                    </div>
                    <div class="ft-details-blk">
                        <div class="ft-nm-blk">
                            <h4>hex.bet <span>all rights reserved</span></h4>
                        </div>
                        <div class="ft-img-list">
                            <ul>
                                <li><a href="#"><img src="images/ft-img.png" alt="img"></a></li>
                                <li><a href="#"><img src="images/ft-img.png" alt="img"></a></li>
                            </ul>
                        </div>
                        <div class="ft-info-blk">
                            <p>Loream ipsum is a simple text so nice that comes from really good sence, so it has no problem to do it</p>
                        </div>
                    </div>
                    <div class="ft-social-list">
                        <ul>
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- End Footer -->

    <script type="text/javascript" src="js/jquery.min.js"></script> 
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/owl.carousel.min.js"></script>
    <script type="text/javascript" src="js/wow.min.js"></script>
    <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/dataTables.bootstrap.js"></script>
    <script type="text/javascript" src="js/dataTables.responsive.js"></script>
    <script type="text/javascript" src="js/chart.js"></script>
    <script type="text/javascript">
        //wow animation js
        new WOW().init();

        //data table js
        $('.table').DataTable();

        
        //our chart
        var ctx = document.getElementById("myChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["June 16",   "June 16",   "June 16",  "June 16", "June 16"],
                datasets: [{
                    label: 'Series 1', // Name the series
                    data: [1000, 35000, 23000, 140000,  200000], // Specify the data values array
                    fill: false,
                    borderColor: '#FFE787', // Add custom color border (Line)
                    backgroundColor: '#FFE787', // Add custom color background (Points and Fill)
                    borderWidth: 1 // Specify bar border width
                }]},
            options: {
              responsive: true, // Instruct chart js to respond nicely.
              maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height 
            }
        });

        //sidenav
        function openNav() {
          document.getElementById("chat-side").style.width = "290px";
        }

        function closeNav() {
          document.getElementById("chat-side").style.width = "0";
        }
    </script>
    <script type="text/javascript">
            /*
            requestAnimationFrame polyfill
            */
            (function (w) {
              var lastTime = 0,
                vendors = ["webkit", /*'moz',*/ "o", "ms"];
              for (var i = 0; i < vendors.length && !w.requestAnimationFrame; ++i) {
                w.requestAnimationFrame = w[vendors[i] + "RequestAnimationFrame"];
                w.cancelAnimationFrame =
                  w[vendors[i] + "CancelAnimationFrame"] ||
                  w[vendors[i] + "CancelRequestAnimationFrame"];
              }

              if (!w.requestAnimationFrame)
                w.requestAnimationFrame = function (callback, element) {
                  var currTime = +new Date(),
                    timeToCall = Math.max(0, 16 - (currTime - lastTime)),
                    id = w.setTimeout(function () {
                      callback(currTime + timeToCall);
                    }, timeToCall);
                  lastTime = currTime + timeToCall;
                  return id;
                };

              if (!w.cancelAnimationFrame)
                w.cancelAnimationFrame = function (id) {
                  clearTimeout(id);
                };
            })(this);

            /*
                Slot Machine
            */
            var sm = (function (undefined) {
              var tMax = 3000, // animation time, ms
                height = 600, //.reel p height* no of elements in the below array of object of reels
                speeds = [],
                r = [],
                reels = [
                    [
                        "<img src='images/slotmachine/bar.svg'>",
                        "<img src='images/slotmachine/cherry.svg'>",
                        "<img src='images/slotmachine/lemon.svg'>",
                        "<img src='images/slotmachine/seven.svg'>",
                        "<img src='images/slotmachine/watermelon.svg'>",
                        "<img src='images/slotmachine/prune.svg'>"
                    ],
                    [
                        "<img src='images/slotmachine/bar.svg'>",
                        "<img src='images/slotmachine/cherry.svg'>",
                        "<img src='images/slotmachine/lemon.svg'>",
                        "<img src='images/slotmachine/seven.svg'>",
                        "<img src='images/slotmachine/watermelon.svg'>",
                        "<img src='images/slotmachine/prune.svg'>"
                    ],
                    [
                        "<img src='images/slotmachine/bar.svg'>",
                        "<img src='images/slotmachine/cherry.svg'>",
                        "<img src='images/slotmachine/lemon.svg'>",
                        "<img src='images/slotmachine/seven.svg'>",
                        "<img src='images/slotmachine/watermelon.svg'>",
                        "<img src='images/slotmachine/prune.svg'>"
                    ],
                ],
                $reels,
                $msg,
                start;

              function init() {
                $reels = $(".reel").each(function (i, el) {
                  el.innerHTML =
                    "<div><p>" +
                    reels[i].join("</p><p>") +
                    "</p></div><div><p>" +
                    reels[i].join("</p><p>") +
                    "</p></div>";
                });

                $msg = $(".msg");

                $(".msg").click(action);
              }

              function action() {
                if (start !== undefined) return;

                for (var i = 0; i < 3; ++i) {
                  speeds[i] = Math.random() + 0.5;
                  r[i] = (((Math.random() * 3) | 0) * height) / 3;
                }

                $msg.html("Spinning...");
                animate();
              }

              function animate(now) {
                if (!start) start = now;
                var t = now - start || 0;

                for (var i = 0; i < 3; ++i)
                  $reels[i].scrollTop =
                    ((speeds[i] / tMax / 2) * (tMax - t) * (tMax - t) + r[i]) % height | 0;

                if (t < tMax) requestAnimationFrame(animate);
                else {
                  start = undefined;
                  check();
                }
              }

              function check() {
                $msg.html(
                  r[0] === r[1] && r[1] === r[2]
                    ? "You won!" +
                        reels[1][(r[0] / 70 + 1) % 3 | 0].split(" ")[0]
                    : "Try again"
                );
              }

              return { init: init };
            })();

            $(sm.init);
    </script>
    <script>
        
            var width = $(window).width();
            console.log(width)
            if (width < 1200){
                $('.btn-close-chat').css("top","230px");
                 $('.btn-close-chat').hide();
                $('.btn-chat').css("top","230px")
                $("#btn-ochat").click(function(){                  
                  $("#btn-ochat").hide()
                  $(".sidenav").css("width","290px"); 
                  $(".chat-wrapper").show();
                  $(".btn-close-chat").show();
                  $('.chat-message').animate({
                        scrollTop: $('.chat-message').get(0).scrollHeight
                    }, 400);
            
                });

                $(".btn-close-chat").click(function(){  
                  document.getElementById("chat-side").style.width = "0";
                   $(".btn-close-chat").hide();
                  $(".sidenav").css("width","0");
                  $(".btn-chat").show();
                 
                });
            }else{

                $(".btn-chat").click(function(){  
                  // document.getElementById("chat-side").style.width = "290px";
                  $(".btn-chat").css("display","none");
                  $(".chat-wrapper").css("display","block"); 
                  $(".btn-close-chat").css("display","block");
                  $('.chat-message').animate({
                        scrollTop: $('.chat-message').get(0).scrollHeight
                    }, 400);
                });

                $(".btn-close-chat").click(function(){  
                  // document.getElementById("chat-side").style.width = "0";
                  $(".btn-close-chat").css("display","none");
                  $(".chat-wrapper").css("display","none");
                  $(".btn-chat").css("display","block");
                });
            
            }  
    </script>
    
    
    <script type="text/javascript">
        var remaining_balance = <?php echo $userBalance; ?>;
        var windowID = <?php echo $windowID; ?>;
    </script>
    <script type="text/javascript" src="js/jquery-ui-1.8.17.custom.min.js"></script>
    <script type="text/javascript" src="js/soundmanager2.js"></script>
    <script type="text/javascript" src="js/slots.js"></script>
</body>
</html>