<!DOCTYPE html>
<html>
  <body>
    <!-- (A) SEARCH FORM -->
    <form method="post" action="1-form.php">
      <h1>SEARCH FOR USERS</h1>
      <input type="text" name="search" required/>
      <input type="submit" value="Search"/>
    </form>

    <?php
    // (B) PROCESS SEARCH WHEN FORM SUBMITTED
    if (isset($_POST['search'])) {
      // (B1) SEARCH FOR USERS
      require "2-search.php";
      
      // (B2) DISPLAY RESULTS
      if (count($results) > 0) {
        foreach ($results as $r) {
          printf("<div>%s - %s</div>", $r['name'], $r['email']);
        }
      } else { echo "No results found"; }
    }
    ?>
  </body>
</html>