<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calendrier Médecin</title>
<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">

<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  <link rel="stylesheet" href="calendrier.css">
</head>
<body>
  <header>
    <h2>Calendrier Intelligent - WEBDENT</h2>
  </header>

  <div id="calendar"></div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek', // Vue hebdomadaire
        locale: 'fr', // Langue française
        editable: false, // Les événements ne sont pas modifiables
        events: {
            url: 'backend.php', // URL pour récupérer les rendez-vous
            method: 'GET', // Récupération via GET
            failure: function () {
                alert('Erreur lors du chargement des rendez-vous.');
            }
        },
        eventContent: function (info) {
            // Afficher le nom du client, le service et l'heure
            return {
                html: `
                    <b>${info.event.title}</b><br>
                    <i>${info.event.extendedProps.service}</i><br>
                    <small>${info.event.start.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })}</small>
                `
            };
        },
        eventClick: function (info) {
            alert(
                `Détails du Rendez-vous :
                Client : ${info.event.title}
                Service : ${info.event.extendedProps.service}
                Médecin : ${info.event.extendedProps.doctor}
                Heure : ${info.event.start.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })}`
            );
        }
    });
    calendar.render();
});
  </script>
</body>
</html>