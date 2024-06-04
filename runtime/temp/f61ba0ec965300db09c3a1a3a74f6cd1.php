<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:88:"D:\phpstudy_pro\WWW\dayuanrenzhongkong\public/../application/admin/view/login/login.html";i:1717493061;}*/ ?>
<!-- lq      -->
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo C('WEB_SITE_TITLE'); ?></title>
    <link rel="shortcut icon" href="/\/favicon.ico">
    <link href="/public/admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="/public/admin/css/font-awesome.css" rel="stylesheet">

    <link href="/public/admin/css/animate.css" rel="stylesheet">
    <link href="/public/admin/css/style.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html"/>
    <![endif]-->
    <script>if (window.top !== window.self) {
        window.top.location = window.location;
    }</script>
    <style>
        .form-group {
            position: relative;
        }
    </style>
    <script>
        console.log("<?php echo C('console_msg'); ?>");
        console.log("当前版本：<?php echo C('dtupdate.version'); ?>");
    </script>
</head>

<body class="gray-bg">
<div id="particles-js"
     style="width: 100%;height: 100%;background-color: #f3f3f4;position: fixed;left: 0px;top: 0px;"></div>
<div class="middle-box text-center loginscreen  animated fadeInDown" id="app">
    <div>
        <div>
            <!--            <h1 class="logo-name">DR</h1>-->
            <img src="<?php echo C('SYS_LOGO')?C('SYS_LOGO'):'/public/admin/img/logo.png'; ?>" style="width: 200px;height: auto;"/>
        </div>
        <h3>欢迎使用 <?php echo C('WEB_SITE_TITLE'); ?></h3>
        <form class="m-t" role="form" action="" method="post" id="commentForm">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="用户名" required name="nickname" minlength="2"
                       autocomplete="off">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" placeholder="密码" required name="password" minlength="4"
                       autocomplete="off">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="谷歌身份验证码,未设置不填" name="verifycode"
                       minlength="6" maxlength="6">
            </div>
            <button class="btn btn-primary block full-width m-b" id="sublogin" data-loading-text="登录中...">登 录</button>
        </form>
    </div>
</div>
<!-- 全局js -->
<script src="/public/admin/js/jquery.min.js?v=2.1.4"></script>
<script src="/public/admin/js/bootstrap.min.js?v=3.3.6"></script>
<script src="/public/admin/js/plugins/validate/jquery.validate.min.js"></script>
<script src="/public/admin/js/plugins/validate/messages_zh.min.js"></script>
<script src="/public/admin/js/plugins/layer/layer.min.js"></script>
<script src="/public/admin/js/particles.min.js"></script>
</body>
<script>
    $(function () {
        $('#commentForm').validate({
            submitHandler: function (form) {
                var $form = $(form);
                var $btn = $("#sublogin").button('loading');
                $.ajax({
                    type: 'post',
                    url: "<?php echo url('Login/logindo'); ?>",
                    data: $form.serialize(),
                    success: function (ret) {
                        $btn.button('reset');
                        if (ret.errno == 0) {
                            window.location.href = ret.data.url;
                        } else {
                            layer.msg(ret.errmsg, {icon: 2});
                        }
                    }
                });
            }
        });
    })
</script>
<script>
    particlesJS('particles-js',
        {
            "particles": {
                "number": {
                    "value": 40,
                    "density": {
                        "enable": true,
                        "value_area": 400
                    }
                },
                "color": {
                    "value": "random"
                },
                "shape": {
                    "type": "circle",
                    "stroke": {
                        "width": 0,
                        "color": "#000"
                    },
                    "polygon": {
                        "nb_sides": 5
                    },
                    "image": {
                        "src": "img/github.svg",
                        "width": 100,
                        "height": 100
                    }
                },
                "opacity": {
                    "value": 0.5,
                    "random": false,
                    "anim": {
                        "enable": false,
                        "speed": 1,
                        "opacity_min": 0.1,
                        "sync": false
                    }
                },
                "size": {
                    "value": 2,
                    "random": false,
                    "anim": {
                        "enable": true,
                        "speed": 30,
                        "size_min": 0.3,
                        "sync": false
                    }
                },
                "line_linked": {
                    "enable": true,
                    "distance": 150,
                    "color": "#111",
                    "opacity": 0.4,
                    "width": 0.2
                },
                "move": {
                    "enable": true,
                    "speed": 3,
                    "direction": "none",
                    "random": true,
                    "straight": false,
                    "out_mode": "out",
                    'bounce': true,
                    "attract": {
                        "enable": false,
                        "rotateX": 300,
                        "rotateY": 300
                    }
                }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": {
                    "onhover": {
                        "enable": true,
                        "mode": "grab"
                    },
                    "onclick": {
                        "enable": false,
                        "mode": "push"
                    },
                    "resize": true
                },
                "modes": {
                    "grab": {
                        "distance": 200,
                        "line_linked": {
                            "opacity": 1
                        }
                    },
                    "bubble": {
                        "distance": 400,
                        "size": 40,
                        "duration": 2,
                        "opacity": 8,
                        "speed": 3
                    },
                    "repulse": {
                        "distance": 200
                    },
                    "push": {
                        "particles_nb": 4
                    },
                    "remove": {
                        "particles_nb": 2
                    }
                }
            },
            "retina_detect": true,
            "config_demo": {
                "hide_card": false,
                "background_color": "#b61924",
                "background_image": "",
                "background_position": "50% 50%",
                "background_repeat": "no-repeat",
                "background_size": "cover"
            }
        }
    );
</script>

</html>
