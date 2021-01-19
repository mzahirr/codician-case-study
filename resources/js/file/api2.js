// Panorama Sınıfı
var CallBackKonumDegisince = function () { }
var CallBackKonumDegisinceKaldir = function () { CallBackKonumDegisince = function () { } }
var CallBackAciDegisince = function () { }
var CallBackAciDegisinceKaldir = function () { CallBackAciDegisince = function () { } }
var CallBackPaylas = function () { }
var CallBackPaylasKaldir = function () { CallBackPaylas = function () { } }
var CallBackGeriBildirim = function () { }
var CallBackGeriBildirimKaldir = function () { CallBackGeriBildirim = function () { } }

function PanoramaAPI(options, callback) {
    /// <signature>
    /// <summary>Panorama sınıfı</summary>
    /// <param name="options" type="Class">Seçenekler {String panoramaFrame:iframe ID bilgisi, String apiKey: Uygulama KEY bilginiz}</param>
    /// <param name="callback" type="Function">Panorama yüklenmesi tamamlandı olayı</param>
    /// </signature>

    this.version = "2.0";
    this.updateDate = "23.02.2016";
    this.options = options;

    // Panorama penceresini yakalayabilmek için yardımcı metod
    var GetIframeWindow = function (iframe_object) {
        var doc;

        if (iframe_object.contentWindow) {
            return iframe_object.contentWindow;
        }

        if (iframe_object.window) {
            return iframe_object.window;
        }

        if (!doc && iframe_object.contentDocument) {
            doc = iframe_object.contentDocument;
        }

        if (!doc && iframe_object.document) {
            doc = iframe_object.document;
        }

        if (doc && doc.defaultView) {
            return doc.defaultView;
        }

        if (doc && doc.parentWindow) {
            return doc.parentWindow;
        }

        return undefined;
    }

    // options: panoramaFrame & apiKey
    if (!options && !options.panoramaFrame || !options.apiKey) {
        console.error("panoramaFrame ve apiKey alanlari tanimli olmali ! API fonksiyonlari kullanilamaz !");
        return {};
    }

    // seçenekler doldurulmuş mu
    // Panorama iframe ID bilgisi dolu mu
    if (options.panoramaFrame === "") {
        console.error("panoramaFrame alani tanimli degil !\r\nAPI fonksiyonlari kullanilamaz !");
        return {};
    }
    // Uygulamala KEY bilgisi dolu mu
    if (options.apiKey === "") {
        console.error("apiKey alani tanimli degil !\r\nAPI fonksiyonlari kullanilamaz !");
        return {};
    }
    // Uygulamala KEY bilgisi yeterli uzunlukta mı
    if (options.apiKey.length !== 32) {
        console.error("apiKey alani dogrulanamiyor !\r\nAPI fonksiyonlari kullanilamaz !");
        return {};
    }

    /// API KEY iletişimi
    var Ajax = function (url, data, callback, errorback) {
        var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                callback(xhr.responseText, data);
            }
        }
        xhr.onerror = function () {
            errorback(xhr, arguments);
        }
        xhr.open('GET', url + "&" + data, true);
        //xhr.responseType = 'json';
        //xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        //xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
        xhr.send();
    }
    //Ajax("http://cbsproxy.ibb.gov.tr/?UserKeyStatisticsAdd", "apikey=" + options.apiKey,  ****08_07_2020 Perverdi değiştirdi.
	 Ajax("//cbsproxy.ibb.gov.tr/?UserKeyStatisticsAdd", "apikey=" + options.apiKey,
    function (data, params) {
        data = JSON.parse(data);
        data = parseInt(data.long["#text"]);
        if (data === 0) {
            console.error("Gecersiz API KEY", "\r\n", "apiKey:", params.split("=")[1], "\r\n", "API fonksiyonlari kullanilamaz !");
            PanoramaAPI = {}
        }
    },
    function (x, a) {
        console.error(x, a);
        PanoramaAPI = {}
    });

    // Iframe iletişimi
    var MapListener = function (event) {
        var origin = event.origin;
        var splitted = event.data.split("|");
        var methodName = splitted[0];
        var args = [];
        if (splitted.length > 1)
            for (var i = 1; i < splitted.length; i++) {
                args.push(splitted[i]);
            }
        switch (methodName) {
            case "KonumDegisince":
            case "KonumDegisinceKaldir":
            case "AciDegisince":
            case "AciDegisinceKaldir":
            case "Paylas":
            case "PaylasKaldir":
            case "GeriBildirim":
            case "GeriBildirimKaldir":
            case "Ac":
            case "Kapat":
            case "KonumaGit":
            case "AciDegistir":
                eval("CallBack" + methodName + "(" + args + ");");
                break;
            default:
                break;
        }
    }
    // Event Listener aktifleştirmesi
    if (window.addEventListener) {
        addEventListener("message", MapListener, false)
    } else {
        attachEvent("onmessage", MapListener)
    }

    this.OnPositionChanged = function (callback) {
        if (callback) {
            CallBackKonumDegisince = callback;
            var el = document.getElementById(this.options.panoramaFrame);
            GetIframeWindow(el).postMessage("KonumDegisince|", "*");
        }
    }
    this.UnPositionChanged = function () {
        var el = document.getElementById(this.options.panoramaFrame);
        GetIframeWindow(el).postMessage("KonumDegisinceKaldir|", "*");
    }
    this.OnAngleChanged = function (callback) {
        if (callback) {
            CallBackAciDegisince = callback;
            var el = document.getElementById(this.options.panoramaFrame);
            GetIframeWindow(el).postMessage("AciDegisince|", "*");
        }
    }
    this.UnAngleChanged = function () {
        var el = document.getElementById(this.options.panoramaFrame);
        GetIframeWindow(el).postMessage("AciDegisinceKaldir|", "*");
    }
    this.OnShare = function (callback) {
        if (callback) {
            CallBackPaylas = callback;
            var el = document.getElementById(this.options.panoramaFrame);
            GetIframeWindow(el).postMessage("Paylas|", "*");
        }
    }
    this.UnShare = function () {
        var el = document.getElementById(this.options.panoramaFrame);
        GetIframeWindow(el).postMessage("PaylasKaldir|", "*");
    }
    this.OnFeedBack = function (callback) {
        if (callback) {
            CallBackGeriBildirim = callback;
            var el = document.getElementById(this.options.panoramaFrame);
            GetIframeWindow(el).postMessage("GeriBildirim|", "*");
        }
    }
    this.UnFeedBack = function () {
        CallBackGeriBildirimKaldir();
        var el = document.getElementById(this.options.panoramaFrame);
        GetIframeWindow(el).postMessage("GeriBildirimKaldir|", "*");
    }
    this.Open = function (lat, lon, angle) {
        var el = document.getElementById(this.options.panoramaFrame);
        GetIframeWindow(el).postMessage("Ac|" + lat + "|" + lon + "|" + angle, "*");
    }
    this.Close = function () {
        var el = document.getElementById(this.options.panoramaFrame);
        GetIframeWindow(el).postMessage("Kapat|", "*");
    }
    this.Goto = function (lat, lon, angle) {
        var el = document.getElementById(this.options.panoramaFrame);
        GetIframeWindow(el).postMessage("KonumaGit|" + lat + "|" + lon + "|" + angle, "*");
    }
    this.SetAngle = function (angle) {
        var el = document.getElementById(this.options.panoramaFrame);
        GetIframeWindow(el).postMessage("AciDegistir|" + angle, "*");
    }

    if (callback)
        document.getElementById(options.panoramaFrame).onload = function () {
            callback();
        }
}