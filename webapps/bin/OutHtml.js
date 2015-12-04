/**
 * 指定されたURLのHTMLを取得するスクリプトです.
 *
 *  - 引数1: Host ex. "localhost"
 *  - 引数2: URL  ex. "/fixed/output/..."
 */
var page = require('webpage').create(),
    system = require('system');

var host = system.args[1];
var address = system.args[2];
var url = 'http://' + host + address;

page.paperSize = {
    format: "A4",
    orientation: 'landscape',
    margin: '0'
}
page.viewportSize = {
    width: 1280,
    height: 900
};

page.open(address, function (status) {
    if (status !== 'success') {
        console.log('Unable to load the address!');
        phantom.exit(1);
    } else {
        window.setTimeout(function () {
            page.render(output);
            var html = page.content;
            html = html.replace(/href="/gi, 'href="http://'+host+'/');
            html = html.replace(/src="/gi, 'src="http://'+host+'/');
            console.log(html);
            phantom.exit();
        }, 7000);
    }
});

