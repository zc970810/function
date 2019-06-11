// GET / POST
// 参数
// 是否异步
// 如何处理响应数据
// 回调函数

function Ajax() {
	// 初始化
	this.init =function(){
		this.xhr = new XMLHttpRequest();
	}
	// get方法
	this.get = function(url,parameters,callback,async = true){
		this.init();
		if (async) {
			// 异步请求
			this.xhr.onreadystatechange = function(){
				// this => this.xhr
				if (this.readyState == 4 && this.status == 200) {
					callback(this.responseText);
				}
			}
		}
		this.xhr.open('GET',url+'?'+parameters,async);
		this.xhr.send();
	}
	// post方法
	this.post = function(url,parameters,callback,async = true){
		this.init();
		if (async) {
			this.xhr.onreadystatechange = function(){
				if (this.readyState == 4 && this.status == 200) {
					callback(this.responseText);
				}
			}
		}
		this.xhr.open('POST',url,async);
		this.xhr.setRequestHeader('Content-Type','applicantion/x-www-form-urlencoded');
		this.xhr.send(parameters);
	}
}




// 使用
var ajax = new Ajax();
// ajax.get(url,'数据'，回调函数，[true])
// ajax.post(url,'数据'，['headleResponse']，[true])