// ==UserScript==
// @name            OpenURL COinS processor
// @namespace       http://alf.hubmed.org
// @description     Activates OpenURL COinS
// @include         http://*
// ==/UserScript==
function doLookup() {


var links = document.evaluate("//span[contains(@class,'Z3988')]", document, null, XPathResult.UNORDERED_NODE_SNAPSHOT_TYPE, null);
if (!links) return;

// start configuration

var baseURL = 'http://worldcatlibraries.org/registry/gateway'; // your resolver's base URL
var linkText = 'Find a Copy'; // your preferred link text
var linkImage;
var linkImage = 'http://www.clientwebstage.com/msrc/citagora2/_img/find_a_copy.jpg'; // leave commented out to just create a text link

// end configuration

for (var i = 0; i < links.snapshotLength; i++) {

    var e = links.snapshotItem(i);
    if (e.className.match(/\bZ3988\b/)){

        var a = document.createElement('a');
        a.href = baseURL + '?' + e.title.replace(/ctx_ver/, 'url_ver') + '&url_ctx_fmt=ori:fmt:kev:mtx:ctx';
    
        if (linkImage){
            var button = document.createElement('img');
            button.setAttribute('src', linkImage);
            button.setAttribute('alt', linkText);
            button.setAttribute('border', '0');
            a.appendChild(button);
        }
        else{
            a.innerHTML = linkText;
        }
        
        e.innerHTML = '';
        e.appendChild(a);
    }
    
}

}
