jQuery(document).ready(function ($) {
  const daysTag = document.querySelector(".days");
  const currentDate = document.querySelector(".current-date");
  const prevNextIcon = document.querySelectorAll(".icons span");

  let date = new Date();
  let currYear = date.getFullYear();
  let currMonth = date.getMonth();

  const months = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
  ];

  const renderCalendar = () => {
    let firstDayofMonth = new Date(currYear, currMonth, 1).getDay();
    let lastDateofMonth = new Date(currYear, currMonth + 1, 0).getDate();
    let lastDayofMonth = new Date(
      currYear,
      currMonth,
      lastDateofMonth
    ).getDay();
    let lastDateofLastMonth = new Date(currYear, currMonth, 0).getDate();
    let liTag = "";

    for (let i = firstDayofMonth; i > 0; i--) {
      liTag += `<li class="inactive">${lastDateofLastMonth - i + 1}</li>`;
    }

    for (let i = 1; i <= lastDateofMonth; i++) {
      let isToday =
        i === date.getDate() &&
        currMonth === new Date().getMonth() &&
        currYear === new Date().getFullYear()
          ? "active"
          : "";
      liTag += `<li class="${isToday}" data-date="${currYear}-${
        currMonth + 1
      }-${i}">${i}</li>`;
    }
    const today =
      new Date().getFullYear() +
      "-" +
      (new Date().getMonth() + 1) +
      "-" +
      new Date().getDate();
    updateAvailableSlots(today);

    for (let i = lastDayofMonth; i < 6; i++) {
      liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`;
    }
    currentDate.innerText = `${months[currMonth]} ${currYear}`;
    daysTag.innerHTML = liTag;
    const sessionDateInput = document.getElementById("session-date");
    if (sessionDateInput) {
      sessionDateInput.value = today;
    }
  };

  const formatDate = (inputDate) => {
    const [year, month, day] = inputDate.split("-");
    const formattedMonth = String(month).padStart(2, "0");
    const formattedDay = String(day).padStart(2, "0");

    return `${year}-${formattedMonth}-${formattedDay}`;
  };

  const handleDayClick = () => {
    const dayElements = document.querySelectorAll(".days li");

    dayElements.forEach((dayElement) => {
      dayElement.addEventListener("click", () => {
        dayElements.forEach((element) => {
          element.classList.remove("selected");
        });

        dayElement.classList.add("selected");

        const selectedDate = dayElement.getAttribute("data-date");
        const sessionDateInput = document.getElementById("session-date");

        if (sessionDateInput) {
          sessionDateInput.value = formatDate(selectedDate);
        }

        updateAvailableSlots(selectedDate);
      });
    });
  };
  const handlePrevNextClick = () => {
    prevNextIcon.forEach((icon) => {
      icon.addEventListener("click", () => {
        currMonth = icon.id === "prev" ? currMonth - 1 : currMonth + 1;

        if (currMonth < 0 || currMonth > 11) {
          date = new Date(currYear, currMonth, new Date().getDate());
          currYear = date.getFullYear();
          currMonth = date.getMonth();
        } else {
          date = new Date();
        }
        renderCalendar();
        handleDayClick();
      });
    });
  };

  renderCalendar();
  handleDayClick();
  handlePrevNextClick();

  function updateAvailableSlots(date) {
    jQuery.ajax({
      url: "/wp-admin/admin-ajax.php",
      type: "post",
      data: {
        action: "get_available_slots",
        selected_date: date,
      },
      success: function (response) {
        const availableSlots = response;
        const selectElement = document.getElementById("session-hour");

        selectElement.innerHTML = "";
        availableSlots.forEach((slot) => {
          const option = document.createElement("option");
          option.value = slot;
          option.text = slot;
          selectElement.appendChild(option);
        });
      },
    });
  }
});
