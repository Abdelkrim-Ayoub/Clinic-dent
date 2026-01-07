// Variables globales
const monthYear = document.getElementById("monthYear");
const calendarGrid = document.querySelector(".calendar-grid");
const currentDateElement = document.getElementById("currentDate");
const modal = document.getElementById("modal");
const modalDate = document.getElementById("modalDate");
const appointments = document.getElementById("appointments");
const closeModal = document.getElementById("closeModal");

// Variables du calendrier
let currentDate = new Date();
let currentMonth = currentDate.getMonth();
let currentYear = currentDate.getFullYear();

 Liste fictive de rendez-vous (exemple)
const appointmentsData = {
  "2025-01-25": ["10:00 - Consultation avec Dr. Smith", "14:00 - Nettoyage avec Dr. Brown"],
  "2025-01-26": ["11:00 - Contrôle avec Dr. Adams"]
};

// Génération du calendrier
function generateCalendar(month, year) {
  // Vider la grille existante
  calendarGrid.innerHTML = "";

  // Jours de la semaine
  const daysOfWeek = ["Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim"];
  daysOfWeek.forEach(day => {
    const dayName = document.createElement("div");
    dayName.classList.add("day-name");
    dayName.textContent = day;
    calendarGrid.appendChild(dayName);
  });

  // Premiers jours du mois
  const firstDay = new Date(year, month, 1).getDay(); // Jour de la semaine
  const daysInMonth = new Date(year, month + 1, 0).getDate(); // Nombre de jours dans le mois

  // Remplir les cases vides avant le 1er du mois
  for (let i = 1; i < firstDay; i++) {
    const emptyCell = document.createElement("div");
    emptyCell.classList.add("day-cell", "inactive");
    calendarGrid.appendChild(emptyCell);
  }

  // Remplir les jours du mois
  for (let day = 1; day <= daysInMonth; day++) {
    const dayCell = document.createElement("div");
    dayCell.classList.add("day-cell");
    dayCell.textContent = day;

    // Format de date pour comparer
    const dateKey = `${year}-${String(month + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;

    // Vérifier s'il y a des rendez-vous
    if (appointmentsData[dateKey]) {
      dayCell.style.backgroundColor = "#ffebcc"; // Marquer les jours avec rendez-vous
    }

    // Mettre en surbrillance le jour actuel
    if (
      day === currentDate.getDate() &&
      month === currentDate.getMonth() &&
      year === currentDate.getFullYear()
    ) {
      dayCell.classList.add("today");
    }

    // Ajouter un événement au clic
    dayCell.addEventListener("click", () => showAppointments(dateKey, day));
    calendarGrid.appendChild(dayCell);
  }

  // Mettre à jour le titre du mois
  monthYear.textContent = `${new Date(year, month).toLocaleString("fr-FR", {
    month: "long"
  })} ${year}`;
}

// Afficher les rendez-vous pour une journée spécifique
function showAppointments(dateKey, day) {
  modal.style.display = "flex";
  modalDate.textContent = `Rendez-vous du ${day}`;
  appointments.innerHTML = "";

  if (appointmentsData[dateKey]) {
    appointmentsData[dateKey].forEach(appt => {
      const apptItem = document.createElement("p");
      apptItem.textContent = appt;
      appointments.appendChild(apptItem);
    });
  } else {
    appointments.textContent = "Aucun rendez-vous.";
  }
}

// Fermer la boîte modale
closeModal.addEventListener("click", () => {
  modal.style.display = "none";
});

// Changer de mois
document.getElementById("prevMonth").addEventListener("click", () => {
  currentMonth = currentMonth === 0 ? 11 : currentMonth - 1;
  currentYear = currentMonth === 11 ? currentYear - 1 : currentYear;
  generateCalendar(currentMonth, currentYear);
});

document.getElementById("nextMonth").addEventListener("click", () => {
  currentMonth = currentMonth === 11 ? 0 : currentMonth + 1;
  currentYear = currentMonth === 0 ? currentYear + 1 : currentYear;
  generateCalendar(currentMonth, currentYear);
});

// Mettre à jour la date et l'heure actuelles
function updateCurrentDate() {
  const now = new Date();
  currentDateElement.textContent = now.toLocaleString("fr-FR", {
    weekday: "long",
    day: "numeric",
    month: "long",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit"
  });
}

// Initialisation
generateCalendar(currentMonth, currentYear);
updateCurrentDate();
setInterval(updateCurrentDate, 60000); // Mettre à jour l'heure toutes les minutes