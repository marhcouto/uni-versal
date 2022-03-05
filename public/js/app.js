
import { onPostLoad } from './upvotes.js';
import { onQuestionLoadBookmark, onClickBookmarked } from './bookmark.js';
import { onAnswerLoad, onQuestionLoadSolved } from './answer-verification.js';
import { getDrafts } from './drafts.js';
import { checkUnseenNotif, getNotifications, deleteNotifications } from './notifications.js';
import { addReportSubmissonEvent, checkIfAlreadyReported } from './report.js';

function addEventListeners() {

  // Draft button ajax, to open and create drafts
  let drafts_buttons = document.getElementsByName('drafts-button');
  if (drafts_buttons.length != 0) {
    document.getElementById('Draft-createQuestion').onclick = () => { getDrafts(); };
  }

  window.addEventListener('load', () => {
    // Event to show marked posts in drop down
    onClickBookmarked();

    // Notification events
    document.getElementById('show-notif-button').onclick = () => {getNotifications();};
    document.getElementById('deleteAll-notif-modal').onclick = () => {deleteNotifications();};
    checkUnseenNotif();

    // Reports events
    addReportSubmissonEvent();
    checkIfAlreadyReported();
  });


  // On load of question thread, activate ajax scripts for upvotes
  let thread_posts = document.getElementsByName('thread-post-data-div');
  if (thread_posts.length != 0) {
    window.addEventListener('load', () => {
      for (const post of thread_posts){
        onPostLoad(post.getAttribute('data_id'));
      }
    });
  }

  // On load of an answer in a thrad, activate ajax for answer verification
  let thread_answers = document.getElementsByName('thread-answer-data-div');
  if (thread_answers.length != 0) {
    window.addEventListener('load',() => {
      for (const answer of thread_answers)
        onAnswerLoad(answer.getAttribute('data_id'));
    });
  }

  // On load of a question in a thrad, activate ajax for question validation and bookmarks
  let thread_question = document.getElementById('thread-question-data-div');
  if (thread_question != null) {
    window.addEventListener('load', () => {
      onQuestionLoadBookmark(thread_question.getAttribute('data_id'));
      onQuestionLoadSolved(thread_question.getAttribute('data_id'));
    });
  }
}



addEventListeners();



    