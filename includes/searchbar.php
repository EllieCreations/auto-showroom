<link rel="stylesheet" href="../css/searchbar.css">
<div class="search-bar">
    <?php
    // Determina se sei nella pagina "home"
    $currentPage = basename($_SERVER['PHP_SELF']);
    $formAction = ($currentPage === 'index.php') ? '../pages/inventario.php' : ''; // Se sei nella home, vai a inventario.php
    ?>
    <form id="search-form" method="GET" action="<?= $formAction ?>">
        <input type="text" name="search_query" value="<?= htmlspecialchars($_GET['search_query'] ?? '') ?>"
            placeholder="Cerca auto..." />
        <button type="submit" style="padding: 5px 0px 0px 0px;"><lord-icon src="https://cdn.lordicon.com/wjyqkiew.json" trigger="morph" stroke="bold"
                state="morph-check" colors="primary:#ffffff,secondary:#ffffff" style="width:30px;height:30px">
            </lord-icon></i>
        </button>
    </form>
</div>