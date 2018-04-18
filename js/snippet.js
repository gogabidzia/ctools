var ContentToolsWrapper = function () {
    this.editor = ContentTools.EditorApp.get();
    this.init = function () {
        this.editor.init('*[data-editable]', 'data-name');
        this.setEventListeners();
    }
    this.setEventListeners = function () {
        var that = this;
        this.editor.addEventListener('saved', function (e) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "index.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send(that.getPostData());
            xhr.onreadystatechange = function () {
                if (this.readyState == 4) {
                    var response = JSON.parse(this.responseText);
                    if (response.status == 'error') {
                        alert("Error while saving file");
                        return;
                    }
                    return location.reload();
                }
            }
        });
    }
    this.getNewHtml = function () {
        document.querySelector('.ct-app').remove();
        return document.querySelector('html').outerHTML;
    }
    this.getPostData = function () {
        var html = this.getNewHtml();
        var postData = "html=" + html;
        return postData;
    }
}