$(function () {
    //ajax post submit请求
    $('.ajax-posts').click(function () {
        var target, query, form;
        var target_form = $(this).attr('target-form');
        var that = this;
        var nead_confirm = false;
        if (($(this).attr('type') == 'submit') || (target = $(this).attr('href')) || (target = $(this).attr('url'))) {
            form = $('.' + target_form);
            if ($(this).attr('hide-data') === 'true') {//无数据时也可以使用的功能
                form = $('.hide-data');
                query = form.serialize();
            } else if (form.get(0) == undefined) {
                return false;
            } else if (form.get(0).nodeName == 'FORM') {
                if ($(this).hasClass('confirm')) {
                    if (!confirm('确认要执行该操作吗?')) {
                        return false;
                    }
                }
                if ($(this).attr('url') !== undefined) {
                    target = $(this).attr('url');
                } else {
                    target = form.get(0).action;
                }
                query = form.serialize();
            } else if (form.get(0).nodeName == 'INPUT' || form.get(0).nodeName == 'SELECT' || form.get(0).nodeName == 'TEXTAREA') {
                query = form.serialize();
                form.each(function (k, v) {
                    if (v.type == 'checkbox' && v.checked == true) {
                        nead_confirm = true;
                    }
                })
                if (nead_confirm && $(this).hasClass('confirm')) {
                    if (!confirm('确认要执行该操作吗?')) {
                        return false;
                    }
                }
                if (nead_confirm && $(this).hasClass('prompt')) {
                    layer.prompt({title: $(that).attr('prompt-title'), formType: 2}, function (text, index) {
                        layer.close(index);
                        query += "&prompt_remark=" + text;
                        ajaxSubPost(that, target, query);
                    });
                    return false;
                }
                if (nead_confirm && $(this).hasClass('prompt_m')) {
                    layer.prompt({title: $(that).attr('prompt-v-title'), formType: 3}, function (value, index) {
                        layer.close(index);
                        layer.prompt({title: $(that).attr('prompt-title'), formType: 2}, function (text, index) {
                            layer.close(index);
                            query += "&prompt_remark=" + text + "&value=" + value;
                            ajaxSubPost(that, target, query);
                        })
                    });
                    return false;
                }
            } else {
                if ($(this).hasClass('confirm')) {
                    if (!confirm('确认要执行该操作吗?')) {
                        return false;
                    }
                }
                query = form.find('input,select,textarea').serialize();
            }
            $(that).addClass('disabled').attr('autocomplete', 'off').prop('disabled', true);
            ajaxSubPost(that, target, query);
        }
        return false;
    });
    
    function ajaxSubPost(that, target, query) {
        var load_index = layer.load(1, {
            shade: [0.1, '#fff'] //0.1透明度的白色背景
        });
        $.post(target, query).success(function (data) {
            layer.close(load_index);
            $(that).removeClass('disabled').prop('disabled', false);
            var waittime = 1000;
            if (data.errno == 0) {
                layer.msg(data.errmsg, {icon: 6, time: waittime}, function () {
                    if (!$(that).hasClass('no-close')) {
                        if (data.data.url) {
                            if (data.data.url == 'back') {
                                history.go(-1);
                            } else {
                                location.href = data.data.url;
                            }
                        } else {
                            location.reload();
                        }
                        var index = parent.layer.getFrameIndex(window.name);
                        parent.layer.close(index);
                    } else {
                        if (!$(that).hasClass('no-refresh')) {
                            if (data.data.url) {
                                if (data.data.url == 'back') {
                                    history.go(-1);
                                } else {
                                    location.href = data.data.url;
                                }
                            } else {
                                location.reload();
                            }
                        }
                    }
                });
            } else {
                layer.msg(data.errmsg, {icon: 5, time: waittime}, function () {
                    if (data.data.url) {
                        location.href = data.data.url;
                    }
                });
            }
        }).error(function (xhr, status, info) {
            layer.close(load_index);
            layer.msg('服务器错误码：' + status, {icon: 5}, function () {

            });
        });
    }

})