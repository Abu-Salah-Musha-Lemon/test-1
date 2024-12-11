<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel AJAX CRUD</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Laravel AJAX CRUD</h2>

    <!-- Form for adding/editing contacts -->
    <form id="contactForm" enctype="multipart/form-data">
        <input type="hidden" id="contactId">
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <input type="text" class="form-control" id="name" name="name" placeholder="Name (max 10 chars)">
            </div>
            <div class="col-md-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email">
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone">
            </div>
            <div class="col-md-2">
                <input type="file" class="form-control" id="pdf" name="pdf" accept=".pdf">
            </div>
            <div class="col-md-2">
                <input type="file" class="form-control" id="image" name="image" accept=".jpg,.jpeg,.png">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <button type="reset" class="btn btn-secondary" id="resetForm">Reset</button>
    </form>

    <!-- Table for displaying contacts -->
    <table class="table table-bordered mt-3">
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

    // Handle form submission
    $('#contactForm').submit(function (e) {
        e.preventDefault();
        const id = $('#contactId').val();
        const formData = new FormData(this);
        const url = id ? `/contacts/${id}` : "{{ route('contacts.store') }}";
        const method = id ? 'POST' : 'POST';

        if (id) formData.append('_method', 'PUT');

        $.ajax({
            url: url,
            method: method,
            data: formData,
            processData: false,
            contentType: false,
            success: function () {
                fetchContacts();
                $('#contactForm')[0].reset();
            },
            error: function (xhr) {
                alert(JSON.stringify(xhr.responseJSON.errors));
            }
        });
    });

    // Edit contact
    $(document).on('click', '.edit', function () {
        const id = $(this).data('id');
        $.get(`/contacts/${id}`, function (contact) {
            $('#contactId').val(contact.id);
            $('#name').val(contact.name);
            $('#email').val(contact.email);
            $('#phone').val(contact.phone);
        });
    });

    // Delete contact
    $(document).on('click', '.delete', function () {
        const id = $(this).data('id');
        if (confirm('Are you sure you want to delete this contact?')) {
            $.ajax({
                url: `/contacts/${id}`,
                method: 'DELETE',
                success: function () {
                    fetchContacts();
                },
                error: function () {
                    alert('Unable to delete contact.');
                }
            });
        }
    });
});
</script>
</body>
</html>
