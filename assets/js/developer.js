$( function() {

    $.ajax({
        url: "/getUserNames",
        type: "get",
        success: function(result) {
          $( "#searchFriend" ).autocomplete({
            source: result
          });
        }
    });

    $(".addFriend").click(function(){
        var thisButton = this;

        var friendId = $(this).data("id");
        $.ajax({
          url: "/sendFriendRequest",
          type: "post",
          data:{'friendId':friendId},
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(result) {
            $(thisButton).html("Request Sent");
          }
      });
    });

    $(".acceptRequest").click(function(){

        var id = $(this).data("id");
        $.ajax({
          url: "/acceptFriendRequest",
          type: "post",
          data:{'id':id},
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(result) {
            location.reload();
          }
      });
    });

    $(".rejectRequest").click(function(){

        var id = $(this).data("id");
        $.ajax({
          url: "/rejectFriendRequest",
          type: "post",
          data:{'id':id},
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(result) {
            location.reload();
          }
      });
    });

  } );