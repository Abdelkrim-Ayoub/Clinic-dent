<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!-- FullCalendar JS -->
   <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
  <title>WEBDENT</title>
  <link rel="stylesheet" href="cal.css">
</head>
<body>
  
  <header>
    <h2>WEBDENT</h2>
    <nav class="navbar">
      <a id="acc" href="..\user\user.php">accueil</a> 
      
      <a id="btn" href="..\rdv\rdv.php">
        <button type="button" class="loginBtn">Prender Rendez-vous</button>
      </a>
    </nav>
  </header>
  
  <div class="widget">
  <!-- User Info -->
  <div class="user-info">
    <span id="rdv">votre rendez-vous</span>
  </div>
  
  <hr class="divider">

  <!-- Appointment Info -->
  <div class="appointment-info">
    <div class="appointment-row">
      <label for="medicine">Médecin:</label>
      <span id="medicine">Dr. Example</span>
    </div>
    <div class="appointment-row">
      <label for="service-type">Type de Service:</label>
      <span id="service-type">Service Example</span>
    </div>
    <div class="appointment-row">
      <label for="appointment-date">Date et Heure:</label>
      <span id="appointment-date">01/01/2025 10:00 AM</span>
    </div>
  </div>
  
  </div>