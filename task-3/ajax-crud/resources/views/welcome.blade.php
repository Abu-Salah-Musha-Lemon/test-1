<!DOCTYPE html>
<html>
<head>
    <title>Laravel AJAX CRUD</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Laravel AJAX CRUD</h2>
    <button class="btn btn-success mb-3" id="createNewContact">Add New Contact</button>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="contactsTableBody"></tbody>
    </table>
</div>

<script>
$(document).ready(function () {
    fetchContacts();

    function fetchContacts() {
        $.get("{{ route('contacts.index') }}", function (data) {
            let rows = '';
            data.forEach(contact => {
                rows += `
                    <tr>
                        <td>${contact.id}</td>
                        <td>${contact.name}</td>
                        <td>${contact.email}</td>
                        <td>${contact.phone}</td>
                        <td>
                            <button class="btn btn-primary btn-sm edit" data-id="${contact.id}">Edit</button>
                            <button class="btn btn-danger btn-sm delete" data-id="${contact.id}">Delete</button>
                        </td>
                    </tr>
                `;
            });
            $('#contactsTableBody').html(rows);
        });
    }

    $('#createNewContact').click(function () {
        const name = prompt("Enter Name:");
        const email = prompt("Enter Email:");
        const phone = prompt("Enter Phone:");
        const formData = { name, email, phone };

        $.ajax({
            url: "{{ route('contacts.store') }}",
            method: "POST",
            data: formData,
            success: function () {
                fetchContacts();
            },
            error: function (xhr) {
                alert(xhr.responseJSON.errors.join('\n'));
            }
        });
    });

    $(document).on('click', '.delete', function () {
        const id = $(this).data('id');
        $.ajax({
            url: `/contacts/${id}`,
            method: "DELETE",
            success: function () {
                fetchContacts();
            }
        });
    });
});
</script>
</body>
</html>
