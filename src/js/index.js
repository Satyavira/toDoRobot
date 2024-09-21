document.addEventListener('DOMContentLoaded', function () {
  if (window.location.pathname === '/toDoRobot/src/html/toDo.php') {
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

      const dayElement = document.querySelector("#day");
      const numberElement = document.querySelector("#number");
      const monthElement = document.querySelector("#month");

      // Check if elements exist
      if (dayElement) dayElement.innerHTML = (dayOfWeek + ',');
      if (numberElement) numberElement.innerHTML = (date);
      if (monthElement) monthElement.innerHTML = (month);
    }
    
    getCurrentDateAndDay();
  }
});

function editToDo(toDoId) {
  // Your code for opening the edit modal or performing any other action
  // ...
  // Set the toDoId as a data attribute in the modal
  $("#editModal").data("toDoId", toDoId);
  // Example: Open the edit modal
  $("#editModal").modal("show");

  // Get the data of the clicked toDo item and populate the modal fields
  const clickedToDo = $('#toDo_' + toDoId);
  const content = clickedToDo.find(".toDo-text").text().trim();
  const label = clickedToDo.find(".toDo-label").text().trim();

  // Populate the modal fields
  $("#editContent").val(content);
  $("#editLabel").val(label);
}

function updateSidebar() {
  $.ajax({
    type: "GET",
    url: "refresh_labels.php", // Assuming you have a script that returns the updated labels
    success: function (htmlContent) {
      console.log(htmlContent);
      $("#inside-sidebar-list").html(htmlContent); // Update the sidebar
    },
    error: function (error) {
      console.error("Error refreshing sidebar labels:", error);
    },
  });
}

function toggleCheckList(toDoId) {
  $.ajax({
    type: "POST",
    url: "update_checklist.php",
    data: { toDoId: toDoId },
    success: function (response) {
      console.log(response);
      updateSidebar(); // Pass userId to update labels
      // Update to-do list
      refreshToDoList();
    },
    error: function (error) {
      console.error("Error updating checklist:", error);
    },
  });
}

function saveChanges() {
  const editedContent = $("#editContent").val();
  const editedLabel = $("#editLabel").val();
  const toDoId = $("#editModal").data("toDoId");

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
      $("#editModal").modal("hide");
      updateSidebar(); // Update labels after saving changes
      refreshToDoList();
    },
    error: function (error) {
      console.error("Error saving changes:", error);
      $("#editModal").modal("hide");
    },
  });
}

function deleteToDo(toDoId) {
  const confirmDelete = confirm("Are you sure you want to delete this to-do item?");
  if (confirmDelete) {
    $.ajax({
      type: "POST",
      url: "delete_toDo.php",
      data: { toDoId: toDoId },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          console.log(response.message);
          $(`#toDo_${toDoId}`).remove();
          updateSidebar(); // Update labels after deletion
        } else {
          console.error(response.message);
        }
      },
      error: function (error) {
        console.error("Error deleting to-do item:", error);
      },
    });
  }
}

function addToDo(userId) {
  const toDoContent = $("#addToDoInput").val().trim();
  if (toDoContent === "") {
    window.alert("Please enter your to-do item!");
    return;
  }
  const label = window.prompt("Enter a label for the to-do item:");
  if (label !== null) {
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
            updateSidebar(userId); // Update labels after adding a new to-do
            refreshToDoList(userId);
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

function refreshToDoList() {
  $.ajax({
    type: "GET",
    url: "refresh_todolists.php",
    success: function (htmlContent) {
      $("#allToDo").html(htmlContent);
    },
    error: function (error) {
      console.error("Error refreshing to-do lists:", error);
    },
  });
}