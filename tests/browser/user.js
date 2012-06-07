var assert = require('assert'),
    zombie = require('zombie'),
    vows = require('vows');

var url = "http://dev.gw.daisun-sl-vm.jp";

zombie.debug = true;

vows.describe("/user/以下").addBatch({
    "/user/loginにアクセス": {
        topic: function () {
            zombie.visit(url + "/user/login", this.callback);
        },
        "statusが200で返ってくること": function (err, browser, status) {
            assert.equal(status, 200);
        },
        "タイトルがログインであること": function (err, browser, status) {
            assert.equal(browser.text("title"), "ログイン");
        },
        "user_nameフィールドがあること": function (err, browser, status) {
            assert.ok(browser.query("#user_name"));
        },
        "passwordフィールドがあること": function (err, browser, status) {
            assert.ok(browser.query("#password"));
        }
    },
    "/user/registerにアクセス": {
        topic: function () {
            zombie.visit(url + "/user/register", this.callback);
        },
        "statusが200で返ってくること": function (err, browser, status) {
            assert.equal(status, 200);
        }
    }
}).export(module);
