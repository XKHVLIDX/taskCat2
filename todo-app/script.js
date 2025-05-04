document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("todoForm");
  const list = document.getElementById("taskList");

  function loadTasks() {
    fetch("api/todos.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ action: "get" }),
    })
      .then((response) => response.json())
      .then((tasks) => {
        list.innerHTML = "";
        tasks.forEach((task) => {
          const li = document.createElement("li");
          const checkbox = document.createElement("input");
          checkbox.type = "checkbox";
          checkbox.checked = task.completed == 1;
          checkbox.addEventListener("change", () => {
            fetch("api/todos.php", {
              method: "POST",
              headers: {
                "Content-Type": "application/json",
              },
              body: JSON.stringify({
                action: "update",
                id: task.id,
                completed: checkbox.checked,
              }),
            });

            if (checkbox.checked) {
              li.style.textDecoration = "line-through";
            } else {
              li.style.textDecoration = "none";
            }
          });

          const span = document.createElement("span");
          span.textContent = task.title;
          if (checkbox.checked) {
            span.style.textDecoration = "line-through";
          }

          const deleteBtn = document.createElement("button");
          deleteBtn.textContent = "Delete";
          deleteBtn.addEventListener("click", () => {
            if (confirm("Delete this task?")) {
              fetch("api/todos.php", {
                method: "POST",
                headers: {
                  "Content-Type": "application/json",
                },
                body: JSON.stringify({
                  action: "delete",
                  id: task.id,
                }),
              });
              li.remove();
            }
          });

          li.appendChild(checkbox);
          li.appendChild(span);
          li.appendChild(deleteBtn);
          list.appendChild(li);
        });
      });
  }

  // Add new task
  form.addEventListener("submit", function (e) {
    e.preventDefault();
    const input = document.getElementById("newTask");
    const title = input.value.trim();

    if (title === "") return;

    fetch("api/todos.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        action: "add",
        title: title,
      }),
    })
      .then((response) => response.json())
      .then((task) => {
        input.value = "";
        loadTasks();
      });
  });

  loadTasks();
});
