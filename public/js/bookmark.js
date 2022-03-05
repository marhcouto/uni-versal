import { sendAjaxRequest } from './ajax-request.js';

// Bookmark Ajax

function bookmark(id_post) {
    sendAjaxRequest('POST','/question/bookmark', {id_post: id_post}, bookmarkHandler);
}
  
function bookmarkHandler() {
    let response = JSON.parse(this.response);
    checkBookmark(response.id_post);
}

function checkBookmark(id_post) {
    sendAjaxRequest('GET','/question/' + id_post + '/bookmark', null, checkBookmarkHandler);
}

function checkBookmarkHandler() {
    let response = JSON.parse(this.response);
    document.getElementById(response.id_post + '-bookmark-button').style = "background: #287BE5;";
    if (response.bookmark != null) document.getElementById(response.id_post + '-bookmark-button').style = "background: #134A91 ;";       
}


function getBookmarksHandler(){
    let json = JSON.parse(this.responseText);
    let goalDiv = document.querySelector('.bookmarks-list');
    goalDiv.innerHTML = json.response;
}


export function onQuestionLoadBookmark(id_post) {
    document.getElementById(id_post + '-bookmark-button').onclick = () => {bookmark(id_post);};
    checkBookmark(id_post); 
}


export function onClickBookmarked(){
    const dropdownButtons = document.getElementsByClassName("dropdown-item");
    dropdownButtons[0].addEventListener("click", function(){
        sendAjaxRequest('GET', '/user/bookmarks', null, getBookmarksHandler);
    });
    
}




