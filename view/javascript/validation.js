console.log("aaaa")
// 仮登録画面にて
const checkmail = () =>{
  let mail = $("#mail").val();
  if(mail.match(/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-])+$/)){
    $("#all_msg").text("");
  }else{
    $("#all_msg").text("メールアドレスの形式が正しくありません。"); 
  }
}

const checkbutton = () =>{
  let mail = $("#mail").val();
  if(!mail){
    $("#all_msg").text("値を入力してください。");
    return false
  }else{
    if(mail.match(/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-])+$/)){
      $("#all_msg").text("");
      $('.js-modal').fadeIn();
      $("#mail_che").text($("#mail").val());
      $('.js-modal-close').on('click',function(){
        $('.js-modal').fadeOut();
        return false;
      });
    }else{
      $("#all_msg").text("メールアドレスの形式が正しくありません。"); 
    }
  }
}

// 本登録画面にて
const check_account = () =>{
  let account = $("#account").val();
  if(account.match(/^([ぁ-んァ-ヶー一-龠ｧ-ﾝﾞﾟa-zA-Z0-9０-９]{1,})+$/)){
    $("#all_msg").text("");
  }else{
    $("#all_msg").text("パスワードは半角英数字で8文字以上でアカウント名に記号は使えません。"); 
  }
}

const check_password = () =>{
  let password = $("#password").val();
  if(password.match(/^([a-zA-Z0-9]{8,})+$/)){
    $("#all_msg").text("");
  }else{
    $("#all_msg").text("パスワードは半角英数字で8文字以上でアカウント名に記号は使えません。");
  }
}

const account_check = () =>{
  let account = $("#account").val();
  let password = $("#password").val();
  if(!account){
    $("#all_msg").text("値を入力してください。");
    return false
  }else{
    if(account.match(/^([ぁ-んァ-ヶー一-龠ｧ-ﾝﾞﾟa-zA-Z0-9０-９]{1,})+$/)){
      $("#all_msg").text("");
      if(!password){
        $("#all_msg").text("値を入力してください。");
        return false
      }else{
        if(password.match(/^([a-zA-Z0-9]{8,})+$/)){
          $("#all_msg").text("");
          $('.js-modal').fadeIn();
          $("#account_che").text($("#account").val());
          $("#password_che").text($("#password").val());
          $('.js-modal-close').on('click',function(){
            $('.js-modal').fadeOut();
            return false;
          });
        }else{
          $("#all_msg").text("パスワードは半角英数字で8文字以上でアカウント名に記号は使えません。"); 
        }
      }
    }else{
      $("#all_msg").text("パスワードは半角英数字で8文字以上でアカウント名に記号は使えません。"); 
    }
  }
}

// 新規投稿画面
// const checktitle = () =>{
//   let title = $("#title").val();
//   if(title.length > 50){
//     $("#all_msg").text("タイトルは50字、本文は255字以内で入力してください。");
//   }else{
//     $("#all_msg").text("");
//   }
// }
// const checkarticle = () =>{
//   let article = $("#article").val();
//   if(article.length > 255){
//     $("#all_msg").text("タイトルは50字、本文は255字以内で入力してください。");
//   }else{
//     $("#all_msg").text("");
//   }
// }
const check_article = () =>{
  let title = $("#title").val();
  let article = $("#article").val();
  if(!title){
    $("#all_msg").text("値を入力してください。");
    return false
  }else{
    $("#all_msg").text("");
    if(title.length > 50){
      $("#all_msg").text("タイトルは50字、本文は255字以内で入力してください。");
      return false
    }else{
      $("#all_msg").text("");
      if(!article){
        $("#all_msg").text("値を入力してください。");
        return false
      }else{
        $("#all_msg").text("");
        if(article.length > 255){
          $("#all_msg").text("タイトルは50字、本文は255字以内で入力してください。");
        }else{
          $("#all_msg").text("");
          $('.js-modal').fadeIn();
          $("#title_che").text($("#title").val());
          $("#article_che").text($("#article").val());
          $("#image_che").text($("#image").val());
          $('.js-modal-close').on('click',function(){
            $('.js-modal').fadeOut();
            return false;
          });
        }
      }
    }
  }
}

// ユーザー更新画面にて
const check_user = () =>{
  let mail = $("#mail").val();
  let account = $("#account").val();
  let password = $("#password").val();
  if(!mail){
    $("#all_msg").text("値を入力してください。");
    return false
  }else{
    if(mail.match(/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-])+$/)){
      if(!account){
        $("#all_msg").text("値を入力してください。");
        return false
      }else{
        if(account.match(/^([ぁ-んァ-ヶー一-龠ｧ-ﾝﾞﾟa-zA-Z0-9０-９]{1,})+$/)){
          $("#all_msg").text("");
          if(!password){
              $("#all_msg").text("");
              $('.js-modal').fadeIn();
              $("#mail_che").text($("#mail").val());
              $("#account_che").text($("#account").val());
              $("#password_che").text("パスワードは変更しません");
              $('.js-modal-close').on('click',function(){
                $('.js-modal').fadeOut();
                return false;
              });
          }else{
            if(password.match(/^([a-zA-Z0-9]{8,})+$/)){
              $("#all_msg").text("");
              $('.js-modal').fadeIn();
              $("#mail_che").text($("#mail").val());
              $("#account_che").text($("#account").val());
              $("#password_che").text($("#password").val());
              $('.js-modal-close').on('click',function(){
                $('.js-modal').fadeOut();
                return false;
              });
            }else{
              $("#all_msg").text("パスワードは半角英数字で8文字以上でアカウント名に記号は使えません。"); 
            }
          }
        }else{
          $("#all_msg").text("パスワードは半角英数字で8文字以上でアカウント名に記号は使えません。"); 
        }
      }
    }else{
      $("#all_msg").text("メールアドレスの形式が正しくありません。"); 
    }
  }
}

// コメント時
// const checkcomment = () =>{
//   let article = $("#article").val();
//   if(article.length > 50){
//     $("#all_msg").text("50字以内で入力してください");
//   }else{
//     $("#all_msg").text("");
//   }
// }
const comment_check = () =>{
  let comment = $("#comment").val();
  if(!comment){
    alert("値を入力してください。");
    return false;
  }else{
    if(comment.length > 50){
      alert("50文字以下で入力してください。");
      return false;
    }
  }
}
const delete_check = () =>{
  let result = confirm("本当に削除しますか？");
  if( result ) {
    
  }else {
    return false;
  }
}