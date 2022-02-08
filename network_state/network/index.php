<!DOCTYPE html>
<html lang="ko">
    <?php
        # 아래 DB 정보를 설정하여주세요. 
        $mysql_host = 'db_host';
        $mysql_user = 'db_id';
        $mysql_password = 'db_password';
        $mysql_db = 'speedtest';
        $conn = mysql_connect($mysql_host,$mysql_user,$mysql_password);
        $dbconn = mysql_select_db($mysql_db,$conn);
        mysql_query("set names utf8");

        $query = "select * from test_info ORDER BY time DESC LIMIT 8";
        $result = mysql_query($query);
        $i = 1;
        while($row = mysql_fetch_array($result)){
            $time[$i] = $row[time];
            $ping[$i] = explode(".",$row[ping])[0];
            $server_name[$i] = $row[server_name];
            $server_address[$i] = $row[server_address];
            $download_speed[$i] = str_replace(' Mbit/s', '', $row[download_speed]);
            $upload_speed[$i] = str_replace(' Mbit/s', '', $row[upload_speed]);
            $i ++;
        }

        # 중요 정보!
        # 각 디바이스의 포트정보를 남기기 위하여 아래 서버 상태체크 부분의 아이피 및 포트, 변수는 그대로 두었습니다.
        # 변수는 그대로 사용, 아이피만 변경하여도 무관 합니다.

        error_reporting(E_ALL);	// error reporting on

        $ip = "192.168.1.1";	// my localhost ip
        $port = 80;			// 26354 my opened port in modem
        $timeout = 1;			// connection timeout in seconds 

        if (fsockopen($ip, $port, $errno, $errstr, $timeout)) {
            $asus_ax11000 = "OK";
            
        } else {
            $asus_ax11000 = "FAIL";
        }

        $ip = "192.168.1.128";	// my localhost ip
        $port = 80;			// 26354 my opened port in modem
        $timeout = 1;			// connection timeout in seconds 

        if (fsockopen($ip, $port, $errno, $errstr, $timeout)) {
            $nas_web = "OK";
            
        } else {
            $nas_web = "FAIL";
        }

        $ip = "192.168.1.128";	// my localhost ip
        $port = 3306;			// 26354 my opened port in modem
        $timeout = 1;			// connection timeout in seconds 

        if (fsockopen($ip, $port, $errno, $errstr, $timeout)) {
            $nas_mysql = "OK";
            
        } else {
            $nas_mysql = "FAIL";
        }

        $ip = "192.168.1.5";	// my localhost ip
        $port = 39500;			// 26354 my opened port in modem
        $timeout = 1;			// connection timeout in seconds 

        if (fsockopen($ip, $port, $errno, $errstr, $timeout)) {
            $smartthings_hub_v3 = "OK";
        } else {
            $smartthings_hub_v3 = "FAIL";
        }

        $ip = "192.168.1.17";	// my localhost ip
        $port = 80;			// 26354 my opened port in modem
        $timeout = 1;			// connection timeout in seconds 

        if (fsockopen($ip, $port, $errno, $errstr, $timeout)) {
            $ikea_gateway = "OK";
        } else {
            $ikea_gateway = "FAIL";
        }

        $ip = "192.168.1.209";	// my localhost ip
        $port = 80;			// 26354 my opened port in modem
        $timeout = 1;			// connection timeout in seconds 

        if (fsockopen($ip, $port, $errno, $errstr, $timeout)) {
            $hue_bridge = "OK";
        } else {
            $hue_bridge = "FAIL";
        }

        $ip = "192.168.1.150";	// my localhost ip
        $port = 554;			// 26354 my opened port in modem
        $timeout = 1;			// connection timeout in seconds 

        if (fsockopen($ip, $port, $errno, $errstr, $timeout)) {
            $tapo_camera_c200 = "OK";
        } else {
            $tapo_camera_c200 = "FAIL";
        }

        $ip = "192.168.1.128";	// my localhost ip
        $port = 8581;			// 26354 my opened port in modem
        $timeout = 1;			// connection timeout in seconds 

        if (fsockopen($ip, $port, $errno, $errstr, $timeout)) {
            $homebridge = "OK";
        } else {
            $homebridge = "FAIL";
        }

        $ip = "eco-ha.duckdns.org";	// my localhost ip
        $port = 8123;			// 26354 my opened port in modem
        $timeout = 1;			// connection timeout in seconds 

        if (fsockopen($ip, $port, $errno, $errstr, $timeout)) {
            $homeassistant = "OK";
        } else {
            $homeassistant = "FAIL";
        }


        $link =  $_SERVER["REQUEST_URI"];

        if ($link == '/network/index?data=refresh') {
            if($_GET['data'] == 'refresh') {
            $query = "select * from speedtest_refresh LIMIT 1";
            $result = mysql_query($query);
            $row = mysql_fetch_array($result);
            $speedtest_message = $row['message'];
            if($speedtest_message == '측정중') {
                echo '<script>alert("속도 측정중 입니다.\n잠시후 다시 시도해주세요."); location.href = "./";</script>';
            } else {
                $query = "UPDATE speedtest_refresh SET command = 'refresh' WHERE id = 1";
                $result = mysql_query($query);
                echo '<script>alert("속도 측정 서버로 새로고침을 요청하였습니다."); location.href = "./";</script>';
            }
        }
    }

    ?>
    <head>
        <meta charset="utf-8" />
        <meta name="robots" content="noindex">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>NETWORK STATE</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap Icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Do+Hyeon:400" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Gothic+A1:400" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <!-- 하이차트 -->
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/series-label.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/export-data.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>
        <!-- <link href="css/styles2.css?ver=1" rel="stylesheet" /> -->
        <link href="css/styles.css?ver=2" rel="stylesheet" />
        <!-- <link href="css/styles2.css?ver=1" rel="stylesheet" /> -->
        <script>
            window.onload = function() { 
                if(document.location.protocol == 'http:'){
                    document.location.href = document.location.href.replace('http:', 'https:');
                }
            }
            function speedtest_refresh() {
                location.href="index?data=refresh";
                loader();
            }
        </script>
        <style type="text/css"> 
            body {
                background: url(assets/wallpaper.png) no-repeat center center fixed;
                background-size: cover;
            }
        </style>
    </head>
    <body class="text-white">
        <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand" href="./">네트워크 모니터링 서비스</a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                </div>
            </div>
        </nav>
        <div class="page-top">
            <div class="container px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <?php
                        if($ping[1] >= 15) {
                            ?>
                            <div class="speed_info text-center" style="background-color: #dc3545;">
                                <h3 class="h4 mb-2">PING</h3>
                                <h3 class="h1 mb-2"><?php echo $ping[1]; ?> ms</h3>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="speed_info text-center">
                                <h3 class="h4 mb-2">PING</h3>
                                <h3 class="h1 mb-2"><?php echo $ping[1]; ?> ms</h3>
                            </div>
                            <?php
                        }
                        if($download_speed[1] <= 150) {
                            ?>
                            <div class="speed_info text-center" style="background-color: #dc3545;">
                                <h3 class="h4 mb-2">DOWNLOAD</h3>
                                <h3 class="h1 mb-2"><?php echo $download_speed[1]; ?> Mbps</h3>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="speed_info text-center">
                                <h3 class="h4 mb-2">DOWNLOAD</h3>
                                <h3 class="h1 mb-2"><?php echo $download_speed[1]; ?> Mbps</h3>
                            </div>
                            <?php
                        }
                    ?>

                    <div class="speed_info text-center">
                        <h3 class="h4 mb-2">UPLOAD</h3>
                        <h3 class="h1 mb-2"><?php echo $upload_speed[1]; ?> Mbps</h3>
                    </div>

                    <div class="last_check_info text-center">
                        <p style="margin-top: 5px;">마지막 업데이트</p>
                        <p><?php echo $time[1]; ?> - <?php echo $server_name[1] . ' (' . $server_address[1] . ')' ?> <a href="#" onclick="speedtest_refresh()"><img style="width: 15px; height: 15px;" src="assets/img/refresh.png" alt="수동 새로고침"></a></p>
                    </div>
                </div>
            </div>
        </div>
        <figure class="highcharts-figure">
            <div id="container"></div>
            <p class="highcharts-description"></p>
        </figure>
        <div class="page-bottom">
            <div class="last_check_info text-center">
                <p class="network_state" style="margin-top: 5px;">네트워크 서비스 상태</p>
                <p class="real_state"><font color="#198754">●</font> 정상  <font color="#dc3545">●</font> 연결 끊어짐</p> 
            </div>
            <div class="container px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <?php
                        if($asus_ax11000 == 'OK') {
                        ?>
                            <div class="server_check_ok text-center">
                                <h3 class="h4 mb-2 server_ok">ASUS AX11000</h3>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="server_check_error text-center">
                                <h3 class="h4 mb-2 server_error">ASUS AX11000</h3>
                            </div>
                        <?php
                        }
                        if($nas_web == 'OK') {
                        ?>
                            <div class="server_check_ok text-center">
                                <h3 class="h4 mb-2 server_ok">NAS WEB Station</h3>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="server_check_error text-center">
                                <h3 class="h4 mb-2 server_error">NAS WEB Station</h3>
                            </div>
                        <?php
                        }
                        if($nas_mysql == 'OK') {
                            ?>
                                <div class="server_check_ok text-center">
                                    <h3 class="h4 mb-2 server_ok">NAS Mysql</h3>
                                </div>
                            <?php
                            } else {
                            ?>
                                <div class="server_check_error text-center">
                                    <h3 class="h4 mb-2 server_error">NAS Mysql</h3>
                                </div>
                            <?php
                            }
                        ?>
                </div>
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <?php
                        if($smartthings_hub_v3 == 'OK') {
                        ?>
                            <div class="server_check_ok text-center">
                                <h3 class="h4 mb-2 server_ok">SmartThings HUB</h3>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="server_check_error text-center">
                                <h3 class="h4 mb-2 server_error">SmartThings HUB</h3>
                            </div>
                        <?php
                        }
                        if($ikea_gateway == 'OK') {
                        ?>
                            <div class="server_check_ok text-center">
                                <h3 class="h4 mb-2 server_ok">iKea Gateway</h3>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="server_check_error text-center">
                                <h3 class="h4 mb-2 server_error">iKea Gateway</h3>
                            </div>
                        <?php
                        }
                        if($hue_bridge == 'OK') {
                            ?>
                                <div class="server_check_ok text-center">
                                    <h3 class="h4 mb-2 server_ok">Philips Hue Bridge</h3>
                                </div>
                            <?php
                            } else {
                            ?>
                                <div class="server_check_error text-center">
                                    <h3 class="h4 mb-2 server_error">Philips Hue Bridge</h3>
                                </div>
                            <?php
                            }
                        ?>
                </div>
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <?php
                        if($tapo_camera_c200 == 'OK') {
                        ?>
                            <div class="server_check_ok text-center">
                                <h3 class="h4 mb-2 server_ok">IP Camera</h3>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="server_check_error text-center">
                                <h3 class="h4 mb-2 server_error">IP Camera</h3>
                            </div>
                        <?php
                        }
                        if($homebridge == 'OK') {
                            ?>
                                <div class="server_check_ok text-center">
                                    <h3 class="h4 mb-2 server_ok">HOME BRIDGE</h3>
                                </div>
                            <?php
                            } else {
                            ?>
                                <div class="server_check_error text-center">
                                    <h3 class="h4 mb-2 server_error">HOME BRIDGE</h3>
                                </div>
                            <?php
                            }
                        if($homeassistant == 'OK') {
                        ?>
                            <div class="server_check_ok text-center">
                                <h3 class="h4 mb-2 server_ok">HOME ASSISTANT</h3>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="server_check_error text-center">
                                <h3 class="h4 mb-2 server_error">HOME ASSISTANT</h3>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            Highcharts.chart('container', {
                exporting:false,
                colors: ['#7cb5ec', '#ffc107', '#198754'],
                chart: {
                    // type: 'column',
                    borderRadius: 10,
                    backgroundColor:'#212529'
                },
                credits: {
                    enabled: false
                },
                title: {
                    text: '최근 네트워크 상태',
                    style: {
                        color: '#f8f9fa'
                    }
                },

                subtitle: {
                    text: '최근 측정 : <?php echo $time[1] ?> - <?php echo $server_name[1] . '(' . $server_address[1] . ')' ?>',
                    style: {
                        color: '#f8f9fa'
                    }
                },

                yAxis: {
                    title: {
                        text: '속도(Mbps)',
                        style: {
                            color: '#f8f9fa'
                        }
                    },
                    labels: {
                        style: {
                            color: '#f8f9fa'
                        }
                    }
                },

                xAxis: {
                    categories: ['<?php echo $time[8]; ?>', '<?php echo $time[7]; ?>', '<?php echo $time[6]; ?>', '<?php echo $time[5]; ?>', '<?php echo $time[4]; ?>', '<?php echo $time[3]; ?>', '<?php echo $time[2]; ?>', '<?php echo $time[1]; ?>'],
                    labels: {
                        style: {
                            color: '#f8f9fa'
                        }
                    }
                },
                tooltip: {
                    shared: true
                },

                legend: {
                    layout: 'vertical',
                    floating: false,
                    align: 'top',
                    verticalAlign: 'bottom',
                    itemStyle: {
                        color: '#f8f9fa'
                    },
                    itemHoverStyle: {
                        color: '#0dcaf0'
                    }
                },

                plotOptions: {
                    series: {
                        label: {
                            connectorAllowed: false,
                            enabled: true,
                            color: 'black'
                        },
                        dataLabels: {
                            enabled: true,
                            color: 'black'
                        }
                    }
                },

                series: [{
                    name: '다운로드',
                    data: [<?php echo $download_speed[8]; ?>, <?php echo $download_speed[7]; ?>, <?php echo $download_speed[6]; ?>, <?php echo $download_speed[5]; ?>, <?php echo $download_speed[4]; ?>, <?php echo $download_speed[3]; ?>, <?php echo $download_speed[2]; ?>, <?php echo $download_speed[1]; ?>]
                }, {
                    name: '업로드',
                    data: [<?php echo $upload_speed[8]; ?>, <?php echo $upload_speed[7]; ?>, <?php echo $upload_speed[6]; ?>, <?php echo $upload_speed[5]; ?>, <?php echo $upload_speed[4]; ?>, <?php echo $upload_speed[3]; ?>, <?php echo $upload_speed[2]; ?>, <?php echo $upload_speed[1]; ?>]
                }, {
                    name: 'PING',
                    data: [<?php echo $ping[8]; ?>, <?php echo $ping[7]; ?>, <?php echo $ping[6]; ?>, <?php echo $ping[5]; ?>, <?php echo $ping[4]; ?>, <?php echo $ping[3]; ?>, <?php echo $ping[2]; ?>, <?php echo $ping[1]; ?>]
                }],

                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                        }
                    }]
                }

            });
        </script>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- SimpleLightbox plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
        <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>

    </body>
