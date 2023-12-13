const date_debugger = true;

document.addEventListener("DOMContentLoaded", function () {
  const sanitizeInput = (inputValue) => {
    return inputValue.trim().replace(/<\/?[^>]+(>|$)/g, "");
  };

  const isValidDate = (inputDate, inputHour) => {
    const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
    const hourRegex = /^(0[1-9]|1[0-2]):[0-5][0-9] (AM|PM)$/;
    const date = new Date();
    const currentDate =
      date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
    if (date_debugger) {
      console.error("Input:", inputDate);
      console.error("CURR:", currentDate);
      console.error("HOUR:", inputHour);
      console.error("DATE REGX check:", dateRegex.test(inputDate));
      console.error("HOUR REGX check:", hourRegex.test(inputHour));
      console.error("IS FUTURE?", inputDate >= currentDate ? "YES" : "NO");
    }
    if (
      !(inputDate >= currentDate) ||
      !dateRegex.test(inputDate) ||
      !hourRegex.test(inputHour)
    ) {
      date_debugger && console.error("Time CHECK failed");
      return false;
    }
    const isToday = inputDate == currentDate;
    if (isToday) {
      date_debugger && console.error("IS Today: YES");
      // Only compare hours if it is today, some plain conversion inside. Probably easier with a date lib.
      const currentTime = new Date();
      const date = currentTime.getDate();

      const inputTimeArray = inputHour.split(" ");
      const inputTime = inputTimeArray[0];
      const meridian = inputTimeArray[1];

      const compareTime = new Date(
        `${currentTime.getFullYear()}-${
          currentTime.getMonth() + 1
        }-${date} ${inputTime} ${meridian}`
      );
      const now = new Date()
      console.error('COMP:', compareTime);
      console.error('NOW:', now);
      console.error('IS BIGGER Compare than NOW:', compareTime <= now);

      if (compareTime <= now) {
        if (date_debugger) {
          console.error("FAIL HOUR:");
        }
        return false;
      }
    }

    return true;
  };

  const handleFormSubmit = (event) => {
    const sessionNameInput = document.getElementById("session-name");
    const sessionDateInput = document.getElementById("session-date");
    const sessionHourSelect = document.getElementById("session-hour");
    const sessionNotesTextarea = document.getElementById("session-notes");

    const sanitizedName = sanitizeInput(sessionNameInput.value);
    const sanitizedDate = sanitizeInput(sessionDateInput.value);
    const sanitizedHour = sanitizeInput(sessionHourSelect.value);
    const sanitizedNotes = sanitizeInput(sessionNotesTextarea.value);
    const isValid = isValidDate(sanitizedDate, sanitizedHour);
    console.error("VALID:", isValid);
    let errorMessage = null;

    if (sanitizedName === "") {
      errorMessage = "Mentee's Name is required";
    } else if (!isValid) {
      errorMessage = "Time travel is not allowed here, choose a future date";
    } else if (sanitizedNotes === "") {
      errorMessage = "All fields should be populated, you are missing notes";
    }

    if (errorMessage !== null) {
      alert(errorMessage);
      event.preventDefault();
    }
  };

  const sessionForm = document.getElementById("session-form");
  sessionForm.addEventListener("submit", handleFormSubmit);
});
