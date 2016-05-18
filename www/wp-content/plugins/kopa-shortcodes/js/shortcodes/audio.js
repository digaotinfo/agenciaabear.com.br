(function () {
    tinymce.create("tinymce.plugins.audio", {
        init: function (d, e) {
        },
        createControl: function (d, e) {
            if (d == "audio") {
                d = e.createMenuButton("audio", {
                    title: "Audio",
                    image: kopa_shortcodes_globals.pluginUrl + '/js/shortcodes/icons/audio.png',
                    icons: false
                });
                var a = this;
                d.onRenderMenu.add(function (c, b) {
                    
                    a.addImmediate(b, "Audio", '[audio wav_url="http://url-to-file.wav" mp3_url="http://url-to-file.mp3"][/audio]');
                    a.addImmediate(b, "Soundcloud", '[soundcloud]https://soundcloud-url[/soundcloud]');
                    
                });
                return d;
            }
            return null
        },
        addImmediate: function (d, e, a) {
            d.add({
                title: e,
                onclick: function () {
                    tinyMCE.activeEditor.execCommand("mceInsertContent", false, a)
                }
            })
        }
    });
    tinymce.PluginManager.add("audio", tinymce.plugins.audio)
})();