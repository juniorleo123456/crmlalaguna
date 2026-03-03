// JS básico para interacción del wireframe
$(function () {
  var $sidebar = $("#sidebar");
  var $menu = $("#menuToggle");

  // ensure overlay exists
  if ($(".overlay").length === 0) {
    $('<div class="overlay" id="sidebarOverlay"></div>').appendTo("body");
  }
  var $overlay = $("#sidebarOverlay");

  function openSidebar() {
    $sidebar.addClass("open");
    $overlay.addClass("show");
    // prevent body scroll when sidebar open on small devices
    $("body").css("overflow", "hidden");
  }
  function closeSidebar() {
    $sidebar.removeClass("open");
    $overlay.removeClass("show");
    $("body").css("overflow", "");
  }

  $menu.on("click", function (e) {
    e.preventDefault();
    if ($sidebar.hasClass("open")) closeSidebar();
    else openSidebar();
  });

  $overlay.on("click", function () {
    closeSidebar();
  });

  // Close sidebar when window is resized to desktop widths
  $(window).on("resize", function () {
    if (window.innerWidth > 768) {
      closeSidebar();
    }
  });

  /* Live search + pagination for clients and socios pages */
  var searchTimer = null;
  function loadList(endpoint, q, page, paginationSelector) {
    page = page || 1;
    $.getJSON(endpoint, { q: q, page: page }).done(function (data) {
      if (data.rows !== undefined) {
        $("table.responsive-vertical tbody").html(data.rows);
        $(paginationSelector).html(data.pagination);
      }
    });
  }

  // detect which page we're on by presence of pagination container
  function currentContext() {
    if ($(".clients-pagination").length)
      return {
        endpoint: "clients_fetch.php",
        pagination: ".clients-pagination",
      };
    if ($(".socios-pagination").length)
      return { endpoint: "socios_fetch.php", pagination: ".socios-pagination" };

    if ($(".services-pagination").length)
      return {
        endpoint: "services_fetch.php",
        pagination: ".services-pagination",
      };
    if ($(".users-pagination").length)
      return {
        endpoint: "users_fetch.php",
        pagination: ".users_pagination",
      };
    if ($(".client_services_pagination").length)
      return {
        endpoint: "client_services_fetch.php",
        pagination: ".client_services_pagination",
      };
    if ($(".projects-pagination").length)
      return {
        endpoint: "projects_fetch.php",
        pagination: ".projects-pagination",
      };
    return null;
  }

  // bind input on search forms (works for both clients and socios)
  $(document).on("input", 'form[method="get"] input[name="q"]', function () {
    var ctx = currentContext();
    if (!ctx) return;
    var q = $(this).val();
    clearTimeout(searchTimer);
    searchTimer = setTimeout(function () {
      loadList(ctx.endpoint, q, 1, ctx.pagination);
    }, 300);
  });

  // handle pagination clicks
  $(document).on(
    "click",
    ".clients-pagination a.page-link, .socios-pagination a.page-link, .services-pagination a.page-link, .users-pagination a.page-link, .client_services_pagination a.page-link, .projects-pagination a.page-link",
    function (e) {
      e.preventDefault();
      var p = $(this).data("page");
      var q = $('form[method="get"] input[name="q"]').val() || "";
      var ctx = currentContext();
      if (p && ctx) loadList(ctx.endpoint, q, p, ctx.pagination);
    },
  );
});
