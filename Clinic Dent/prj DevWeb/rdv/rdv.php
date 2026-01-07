<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- FullCalendar JS -->
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  <!-- FullCalendar CSS -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <title>WEBDENT</title>
  <link rel="stylesheet" href="rdv.css">
</head>
<body>
  
  <header>
    <h2>WEBDENT</h2>
    <nav class="navbar">
      <a href="..\user\user.php">Accueil</a>
      <a href="..\cal\cal.php">Calendrier</a>
      <a id="btn" href="#">
        <button type="button" class="loginBtn">Prendre Rendez-vous</button>
      </a>
    </nav>
  </header>

  <h1></h1>
  
  <!-- Formulaire de Prise de RDV -->
  <div id="appointment-form">
    <h2>Prendre un Rendez-vous</h2>
    <label for="client_name">Nom du Client :</label>
    <input type="text" id="client_name" placeholder="Entrez votre nom" required>
    
    <label for="doctor">Choisir un Médecin :</label>
    <select id="doctor" required>
      <option value="" disabled selected>Sélectionnez un médecin</option>
      <option value="Dr. Ahmed">Dr. Ahmed</option>
      <option value="Dr. Sarah">Dr. Sarah</option>
      <option value="Dr. Karim">Dr. Karim</option>
    </select>
    
    <label for="service">Type de Service :</label>
    <select id="service" required>
      <option value="" disabled selected>Choisir un service</option>
      <option value="Consultation">Consultation</option>
      <option value="Détartrage">Détartrage</option>
      <option value="Blanchiment">Blanchiment</option>
      <option value="Soins Dentaires">Soins Dentaires</option>
    </select>
    
    <label>Date et Heure sélectionnées :</label>
    <input type="text" id="selected_datetime" readonly placeholder="Cliquez sur une date dans le calendrier">
    
    <button type="button" id="book-appointment">Réserver</button>
  </div>

  <!-- Calendrier Intégré -->
  <h2 style="text-align: center; color: #ffffff;">Calendrier des Disponibilités</h2>
  <div id="calendar"></div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var selectedDateTime = null;
      var calendarEl = document.getElementById('calendar');
      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        locale: 'fr',
        editable: false,
        selectable: true,
        select: function (info) {
          selectedDateTime = info.startStr;
          document.getElementById('selected_datetime').value = info.startStr.replace('T', ' à ');
        },
        events: {
             url: 'backend.php',
             method: 'GET',
             failure: function () {
                 alert('Erreur lors du chargement des disponibilités.');
             },
             success: function(data) {
                 console.log(data);  // Log the data received from the backend
             }
        }
      });
      calendar.render();

      // Réservation de RDV
      $('#book-appointment').on('click', function () {
        let clientName = $('#client_name').val();
        let doctor = $('#doctor').val();
        let service = $('#service').val();

        if (clientName && doctor && service && selectedDateTime) {
          $.ajax({
            url: 'backend.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
              client_name: clientName,
              doctor: doctor,
              service: service,
              appointment_date: selectedDateTime.split('T')[0],
              appointment_time: selectedDateTime.split('T')[1]
            }),
            success: function (response) {
              alert(response.message);
              calendar.refetchEvents();
              $('#client_name').val('');
              $('#doctor').val('');
              $('#service').val('');
              $('#selected_datetime').val('');
            },
            error: function () {
              alert('Erreur lors de la réservation.');
            }
          });
        } else {
          alert('Veuillez remplir tous les champs et sélectionner une date.');
        }
      });
    });
  </script>

</body>
</html>