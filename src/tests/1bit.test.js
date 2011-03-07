module("High level");

oneBit = new OneBit('../1bit.swf');
console.dir(oneBit);
oneBit.ready(function() {
    // Using specify you can set 'color', 'background', 'playerSize', 'position' and 'analytics' - all are optional
 oneBit.specify('color', '#CC0000');
 // Apply is called after options are specified and includes the CSS selector
 oneBit.apply('a');
});

test("first test within module", function() {
    var embeds = document.getElementsByTagName('embed');
    
    ok(embeds.length == 1, "One player embed insrted");
});