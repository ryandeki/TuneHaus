document.addEventListener("click", function (e) {
  if (e.target.classList.contains("btn-excluir")) {
    e.preventDefault();
    const link = e.target.getAttribute("href");

    Swal.fire({
      title: "Tem certeza?",
      html: "Essa ação não poderá ser desfeita!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sim, excluir",
      cancelButtonText: "Cancelar",
      background: "#121212",
      color: "#e6e6e6",
      confirmButtonColor: "#c0392b",
      cancelButtonColor: "#6a1b9a",
    }).then((result) => {
      if (result.isConfirmed) {
        window.location = link;
      }
    });
  }
});
