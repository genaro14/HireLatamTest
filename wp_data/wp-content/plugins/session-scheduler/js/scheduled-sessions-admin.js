jQuery(document).ready(($) => {
  const updateScheduledDates = (rows, page = 1, sort_order = "asc") => {
    console.log("AJAX CALL done__debug");
    $.ajax({
      url: scheduled_sessions_ajax.ajax_url,
      type: "post",
      data: {
        action: "scheduled_sessions_ajax_action",
        rows_to_display: rows,
        page: page,
        sort_order: sort_order,
      },
      success: (response) => {
        $("#scheduled-sessions-table").html(response);
        handleDeleteButtons();
        handleSelect();
        handlePagination();
      },
    });
  };

  updateScheduledDates(5);
  setInterval(() => {
    updateScheduledDates(document.getElementById("rows-selector").value, 1);
  }, 30000);

  const handleSelect = () => {
    $("#rows-selector").on("change", function () {
      var selectedValue = $(this).val();
      updateScheduledDates(selectedValue);
      console.log("ROW");
    });
  };

  const handleDeleteButtons = () => {
    const deleteButtons = document.querySelectorAll(".delete-session");

    deleteButtons.forEach((deleteButton) => {
      deleteButton.addEventListener("click", function () {
        const sessionId = this.getAttribute("data-session-id");
        console.log("Delete:", sessionId);
        deleteScheduledSession(sessionId);
        updateScheduledDates(document.getElementById("rows-selector").value, 1);
      });
    });
  };

  const handlePagination = () => {
    $(".page-selector").on("click", function () {
      var clickedPage = $(this).data("page");
      updateScheduledDates($("#rows-selector").val(), clickedPage);
    });
  };
  const deleteScheduledSession = (sessionId) => {
    jQuery.ajax({
      url: scheduled_sessions_ajax.ajax_url,
      type: "post",
      data: {
        action: "delete_scheduled_session",
        session_id: sessionId,
      },
      success: (response) => {
        updateScheduledDates(document.getElementById("rows-selector").value, 1);
      },
    });
  };
});
