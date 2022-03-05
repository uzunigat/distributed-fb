<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/fb-distribue/src/controller/Control_Utilisateur.php');
    $user = getMyInform();

?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

  <div>
    <a class="navbar-brand" href="./home.php">
    
      <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-facebook mr-2" viewBox="0 0 16 16">
        <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
      </svg>
    <span class="mt-4"> Distribué </span>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>


  <div class="collapse navbar-collapse order-3" id="navbarNav">

    <ul class="navbar-nav  ml-auto ">

      
    <li class="nav-item active">
      <a class="nav-link text-white border"><?php echo $user->get_lastName() . " , " .$user->get_firstName(); ?> </a>
      </li>


      <li class="nav-item active">
        <a class="nav-link" href="./amis.php"> Amis </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./messages.php"> Messages </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./posts.php"> Posts </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./search.php"> Chercher profil </a>
      </li>
    </ul>
  </div>
</nav>