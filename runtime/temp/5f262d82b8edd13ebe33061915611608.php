<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:88:"D:\phpstudy_pro\WWW\dayuanrenzhongkong\public/../application/admin/view/admin/index.html";i:1717493061;}*/ ?>
<!-- lq      -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title><?php echo C('WEB_SITE_TITLE'); ?></title>
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html"/>
    <![endif]-->

    <link rel="shortcut icon" href="/\/favicon.ico">
    <link href="/public/admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="/public/admin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="/public/admin/css/animate.css" rel="stylesheet">
    <link href="/public/admin/css/style.css?v881" rel="stylesheet">
    <link href="/public/admin/css/plugins/toastr/toastr.min.css" rel="stylesheet">
</head>

<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">
<div id="wrapper">
    <!--左侧导航开始-->
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="nav-close"><i class="fa fa-times-circle"></i>
        </div>
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <span><img alt="image" class="img-circle" src="<?php echo $user['headimg']; ?>" width="60"/></span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                               <span class="block m-t-xs"><strong class="font-bold"><?php echo $user['nickname']; ?></strong></span>
                                <span class="text-muted text-xs block"><?php if($user['id'] == '1'): ?>超级管理员<?php else: ?>管理员<?php endif; ?><b
                                        class="caret"></b></span>
                                </span>
                        </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li><a class="J_menuItem" href="<?php echo url('User/infos'); ?>">个人资料</a>
                            </li>
                            <li><a class="J_menuItem" href="<?php echo url('User/shortauth'); ?>">临时授权</a>
                            </li>
                            <li class="divider"></li>
                            <li><a href="<?php echo url('Login/logout',['id'=>$user['id']]); ?>">安全退出</a>
                            </li>
                        </ul>
                    </div>
                    <div class="logo-element navbar-minimalize">
                        <i class="fa fa-chevron-right"></i>
                    </div>
                </li>
                <!--显示菜单-->
                <?php echo $menuhtml; ?>
                <!--显示菜单结束-->
            </ul>
        </div>
    </nav>
    <!--左侧导航结束-->
    <!--右侧部分开始-->
    <div id="page-wrapper" class="gray-bg dashbard-1">

        <div class="row content-tabs">
            <button class="roll-nav roll-left J_tabLeft"><i class="fa fa-backward"></i>
            </button>
            <nav class="page-tabs J_menuTabs">
                <div class="page-tabs-content">
                    <a href="javascript:;" class="active J_menuTab" data-id="<?php echo url('Index/index'); ?>">首页</a>
                </div>
            </nav>
            <button class="roll-nav roll-right J_tabRight"><i class="fa fa-forward"></i>
            </button>
            <div class="btn-group roll-nav roll-right">
                <button class="dropdown J_tabClose" data-toggle="dropdown">关闭操作<span class="caret"></span></button>
                <ul role="menu" class="dropdown-menu dropdown-menu-right">
                    <li class="J_tabShowActive"><a>定位当前选项卡</a>
                    </li>
                    <li class="divider"></li>
                    <li class="J_tabCloseAll"><a>关闭全部选项卡</a>
                    </li>
                    <li class="J_tabCloseOther"><a>关闭其他选项卡</a>
                    </li>
                </ul>
            </div>
            <a class="roll-nav roll-right right-sidebar-toggle btn-zhuti" aria-expanded="false">
                <i class="fa fa-tasks"></i> 主题
            </a>
            <a href="<?php echo url('Login/logout',['id'=>$user['id']]); ?>" class="roll-nav roll-right J_tabExit"><i
                    class="fa fa fa-sign-out"></i> 退出</a>
        </div>
        <div class="row J_mainContent" id="content-main">
            <iframe class="J_iframe" name="iframe0" width="100%" height="100%" src="<?php echo url('Index/index'); ?>"
                    frameborder="0" data-id="<?php echo url('Index/index'); ?>" seamless></iframe>
        </div>
        <!--        <div class="footer">-->
        <!--            <div class="pull-right">&copy; 2017-2020 <a href="http://www.dayuanren.net/" target="_blank">大猿人网络科技</a>-->
        <!--            </div>-->
        <!--        </div>-->
    </div>
    <!--右侧部分结束-->
    <!--右侧边栏开始-->
    <div id="right-sidebar">
        <div class="sidebar-container">
            <div class="tab-content">
                <div id="tab-1" class="tab-pane active">
                    <div class="sidebar-title">
                        <h3><i class="fa fa-comments-o"></i> 主题设置</h3>
                        <small><i class="fa fa-tim"></i> 你可以从这里选择和预览主题的布局和样式，这些设置会被保存在本地，下次打开的时候会直接应用这些设置。</small>
                    </div>
                    <div class="skin-setttings">
                        <div class="title">主题设置</div>
                        <div class="setings-item">
                            <span>收起左侧菜单</span>

                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox"
                                           id="collapsemenu">
                                    <label class="onoffswitch-label" for="collapsemenu">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                            <span>固定顶部</span>

                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="fixednavbar" class="onoffswitch-checkbox"
                                           id="fixednavbar">
                                    <label class="onoffswitch-label" for="fixednavbar">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                                <span>
                        固定宽度
                    </span>

                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="boxedlayout" class="onoffswitch-checkbox"
                                           id="boxedlayout">
                                    <label class="onoffswitch-label" for="boxedlayout">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="title">皮肤选择</div>
                        <div class="setings-item default-skin nb">
                                <span class="skin-name ">
                         <a href="#" class="s-skin-0">
                             默认皮肤
                         </a>
                    </span>
                        </div>
                        <div class="setings-item blue-skin nb">
                                <span class="skin-name ">
                        <a href="#" class="s-skin-1">
                            蓝色主题
                        </a>
                    </span>
                        </div>
                        <div class="setings-item yellow-skin nb">
                                <span class="skin-name ">
                        <a href="#" class="s-skin-3">
                            黄色/紫色主题
                        </a>
                    </span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
    <!--右侧边栏结束-->
</div>

<!-- 全局js -->
<script src="/public/admin/js/jquery.min.js?v=2.1.4"></script>
<script src="/public/admin/js/bootstrap.min.js?v=3.3.6"></script>
<script src="/public/admin/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/public/admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="/public/admin/js/plugins/layer/layer.min.js"></script>

<!-- 自定义js -->
<script src="/public/admin/js/hplus.js?v1"></script>
<script type="text/javascript" src="/public/admin/js/contabs.js?v1"></script>

<!-- 第三方插件 -->
<script src="/public/admin/js/plugins/pace/pace.min.js"></script>
<script src="/public/admin/js/plugins/toastr/toastr.min.js"></script>
<script>
    $(function () {
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "progressBar": true,
            "positionClass": "toast-bottom-right",
            "onclick": null,
            "showDuration": "400",
            "hideDuration": "1000",
            "timeOut": "15000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        setInterval(function () {
            hasBla();
            setTimeout(function () {
                hasRefPorder();
            }, 10000);
            setTimeout(function () {
                hasunusualPorder();
            }, 10000);
        }, 20000);
    });

    function hasBla() {
        $.post("<?php echo U('index/has_apbla'); ?>", {}, function (result) {
            if (result.errno == 0) {
                toastr.warning(result.errmsg);
            }
        });
    }

    function hasRefPorder() {
        $.post("<?php echo U('index/has_apply_refund'); ?>", {}, function (result) {
            if (result.errno == 0) {
                toastr.warning(result.errmsg);
            }
        });
    }
    function hasunusualPorder() {
        $.post("<?php echo U('index/has_apply_unusual'); ?>", {}, function (result) {
            if (result.errno == 0) {
                toastr.error(result.errmsg);
            }
        });
    }
</script>
</body>

</html>
