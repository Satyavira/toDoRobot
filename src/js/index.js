document.addEventListener('DOMContentLoaded', function () {
  // Your JavaScript code here
  if (window.location.pathname == '/toDoRobot/src/html/toDo.php') {
    function getCurrentDateAndDay() {
      const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
      const months = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
      ];
    
      const now = new Date();
      const dayOfWeek = days[now.getDay()];
      const month = months[now.getMonth()];
      const date = now.getDate();
      document.querySelector("#day").innerHTML = (dayOfWeek + ',');
      document.querySelector("#number").innerHTML = (date);
      document.querySelector("#month").innerHTML = (month);
    }
    getCurrentDateAndDay();
  }
});

function toggleCheckList(toDoId) {
  $.ajax({
    type: "POST",
    url: "update_checklist.php",
    data: { toDoId: toDoId },
    success: function (response) {
      // Handle success
      console.log(response);

      // Update specific parts of the page using AJAX
      $.ajax({
        type: "GET",
        url: "refresh_todolists.php",
        data: { userId: toDoId }, // Pass userId as a parameter
        success: function (htmlContent) {
          // Replace the content of the container with the updated HTML
          $("#allToDo").html(htmlContent);
        },
        error: function (error) {
          console.error("Error refreshing to-do lists:", error);
        },
      });
    },
    error: function (error) {
      console.error("Error updating checklist:", error);
    },
  });
}

function editToDo(toDoId) {
  // Your code for opening the edit modal or performing any other action
  // ...
  // Set the toDoId as a data attribute in the modal
  $("#editModal").data("toDoId", toDoId);
  // Example: Open the edit modal
  $("#editModal").modal("show");

  // Get the data of the clicked toDo item and populate the modal fields
  const clickedToDo = $(`#toDo_${toDoId}`);
  const content = clickedToDo.find(".toDo-text").text().trim();
  const label = clickedToDo.find(".toDo-label").text().trim();

  // Populate the modal fields
  $("#editContent").val(content);
  $("#editLabel").val(label);
}

// Modify your existing JavaScript code

function saveChanges() {
  // Get the edited content and label from the modal fields
  const editedContent = $("#editContent").val();
  const editedLabel = $("#editLabel").val();

  // Get the toDoId from a data attribute in the modal
  const toDoId = $("#editModal").data("toDoId");

  // Make an AJAX request to save the changes
  $.ajax({
    type: "POST",
    url: "save_changes.php",
    data: {
      toDoId: toDoId,
      editedContent: editedContent,
      editedLabel: editedLabel,
    },
    dataType: "json",
    success: function (response) {
      console.log(response);
      // Close the modal after saving changes
      $("#editModal").modal("hide");
      $.ajax({
        type: "GET",
        url: "refresh_todolists.php",
        data: { userId: toDoId }, // Pass userId as a parameter
        success: function (htmlContent) {
          // Replace the content of the container with the updated HTML
          $("#allToDo").html(htmlContent);
        },
        error: function (error) {
          console.error("Error refreshing to-do lists:", error);
        },
      });
    },
    error: function (error) {
      // Handle AJAX request error
      console.error("Error saving changes:", error);

      // Close the modal even in case of an error
      $("#editModal").modal("hide");
    },
  });
}

// Add a click event listener to your delete buttons
function deleteToDo(toDoId) {
  // Confirm with the user before deleting
  const confirmDelete = confirm(
    "Are you sure you want to delete this to-do item?"
  );

  if (confirmDelete) {
    // Make an AJAX request to delete the to-do item
    $.ajax({
      type: "POST",
      url: "delete_toDo.php",
      data: {
        toDoId: toDoId,
      },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          // Handle success, e.g., display a success message
          console.log(response.message);

          // Optionally, remove the deleted to-do item from the UI
          $(`#toDo_${toDoId}`).remove();
        } else {
          // Handle failure, e.g., display an error message
          console.error(response.message);
        }
      },
      error: function (error) {
        // Handle AJAX request error
        console.error("Error deleting to-do item:", error);
      },
    });
  }
}

function addToDo(userId) {
  const label = window.prompt("Enter a label for the to-do item:");
  if (label !== null) {
    const toDoContent = $("#addToDoInput").val().trim();
    if (toDoContent !== "") {
      $.ajax({
        type: "POST",
        url: "add_toDo.php",
        data: {
          content: toDoContent,
          label: label,
          userId: userId
        },
        dataType: "json",
        success: function (response) {
          console.log("AJAX Success:", response);
          if (response.success) {
            $("#addToDoInput").val("");
            $.ajax({
              type: "GET",
              url: "refresh_todolists.php",
              data: { userId: userId },
              success: function (htmlContent) {
                $("#allToDo").html(htmlContent);
              },
              error: function (error) {
                console.error("Error refreshing to-do lists:", error);
              },
            });
          } else {
            console.error(response.message);
          }
        },
        error: function (error) {
          console.error("AJAX Error:", error);
        },
      });
    } else {
      console.warn("Please enter a to-do item.");
    }
  } else {
    console.log("Adding to-do item canceled.");
  }
}
