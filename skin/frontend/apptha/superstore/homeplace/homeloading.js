/**
 * Created by Administrator on 2016/5/6.
 */
function myModule(containerDom,url){
    var refreshModule=(function(){

        return {
            //屏幕高度+滚动条达到section位置，则返回section id name
            checkIsOnScreen:function (objList,height){
                if(!objIsEmpty(objList)){
                    for(var item in objList){
                        if(height>objList[item]){
                            delete objList[item];
                            return item;
                        }
                    }
                }else{
                    return false;
                }
            },
            refreshSection:function (url,section){
                var callback=updateData(section);
                var id=section.replace(/[^0-9]/ig,'');
                var oHttpRequestXml;
                if(id){
                    if(window.ActiveXobject){
                        oHttpRequestXml=new ActiveXObject('Microsoft.XMLHTTP');
                    }else{
                        oHttpRequestXml=new XMLHttpRequest();
                    }
                    oHttpRequestXml.open('GET',url+id);
                    oHttpRequestXml.send();
                    oHttpRequestXml.onreadystatechange=function(){
                        if(oHttpRequestXml.readyState==4 && oHttpRequestXml.status==200){
                            callback(oHttpRequestXml.responseText);
                            jQuery('.bxslider'+id).bxSlider({
                                auto: true
                            });
                            jQuery('.productList'+id).bxSlider({
                                auto: false,
                                controls: true,
                                speed:2000
                            });
                            jQuery('a[rel*=facebox]').facebox();
                        }
                    };
                }

            }

        };

        //判断对象是否为空
        function objIsEmpty(obj){
            for(var name in obj){
                if(obj.hasOwnProperty(name)){
                    return false;
                }
            }
            return true;
        }

        //更新sectiondata
        function updateData(whileId){
            return function(data){
                var sectionDom=document.getElementById(whileId);
                sectionDom.style.height='auto';
                jQuery(sectionDom).children('.loading').remove();
                sectionDom.innerHTML=data;
            }
        }

    })();

    //初次加载页面进行加载
    var objList={};
    var initSelf=(function(){
        //初始化container list的对象
        var clientHeight=jQuery(window).height();

        containerDom.each(function(){
            objList[this.getAttribute('id')]=this.offsetTop;
        });

        for(var i in objList){
            if(i  && clientHeight>objList[i]){
                delete objList[i];
                refreshModule.refreshSection(url,i);
            }
        }
    })();

    //鼠标滚动加载section
    jQuery(window).scroll(function(){
        var scrollTop = jQuery(this).scrollTop();
        var windowHeight = jQuery(this).height();
        var thisSection=refreshModule.checkIsOnScreen(objList,windowHeight+scrollTop);
        if(thisSection){
            refreshModule.refreshSection(url,thisSection);
        }
    });

}