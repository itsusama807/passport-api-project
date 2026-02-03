<!DOCTYPE html>
<html>
<head>
    <title>Trashed Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Trash</h5>
            <a href="{{ route('products.index.page') }}" class="btn btn-primary btn-sm">Back to Products</a>
        </div>
        <div class="card-body">
            <table id="trashTable" class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
const token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIyIiwianRpIjoiOTUwMmQ1YjJmZDI0MmExYWNlOTBjMmE1ZDUxMWJkYTJlMjAyZTY5YzVjMDY0YTUwNDQ4ZDRmZmIxMDFiMGY4MzhkMmRlMTFlMDJkNmU5ODkiLCJpYXQiOjE3Njk2MDY1MzYuMjkyODExLCJuYmYiOjE3Njk2MDY1MzYuMjkyODIyLCJleHAiOjE3Njk2MTAxMzQuMTU0MzMxLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.E_jmEi6ld3ntfDSVoz8zypKa_TLr5GR6wI6jRvRYW9tOOO9LNvY0tigKVlSmwbwGXXPDmpro3I276Ed8CCUnOMUk5m0UMM9EBgmMjtRd6wxg1kM-295jNvnfUCwmCrZIENgd7eNLkQEKmf2yAQP31XJ71bEdGsCYOxdjRU3WFGfURynBFzHXBq-RDpN3oymSsplH3C4KyHaBPAh2FzvUIqZVa80tpBG8FUMa0zgYsuwDvdYpxU78f4J_PI3sI4HBzLxEF1dz50HZMs0nvYpsdezgw8KkjxEpYpHKT0HHWcGS0DEATrXKVHGCwaHoIoY-W576dIfnD5WnoraxjNpJj9g7o07eOXoEZIAZZTCv_nRO03Nj-gLohVGRQJiglr2Ps6KeISlcMBBRnpEikp14anLJW9-P8i0jnZ0p06qPLsjJg_aj4dfpYve35LnIDcVWY8eHl4rawMtLQRwfn-bRt3-ibFLANa1M22ea2glyzge_jJ4Ifv2qfLolRpe2d_UcT7Qs5VOd_bqTuFKutxnLskNiFwhn1RxBx3JS1_Gfb9P3gO_PNKA-VIeHRN4n0IdEwHmyizNVI323h23V1BKMDtOHR22fSzwhQGWwUXklpNy935KHA_WypnXmDnE7XTNBJLW2ov6oHcntr8oANrXiUftkzO_IDB0J1dobgiKNjXc";

$(document).ready(function () {
    loadTrash();
});

function loadTrash() {
    $('#trashTable').DataTable({
        destroy: true,
        ajax: {
            url: "/api/products/trashed",
            type: "GET",
            headers: { Authorization: "Bearer " + token },
            dataSrc: "products"
        },
        columns: [
            { data: "id" },
            { data: "name" },
            { data: "price" },
            {
                data: null,
                render: data => `
                    <button class="btn btn-success btn-sm me-1" onclick="restoreProduct(${data.id})">Restore</button>
                    <button class="btn btn-danger btn-sm" onclick="forceDeleteProduct(${data.id})">Delete</button>
                `
            }
        ]
    });
}

async function restoreProduct(id) {
    if (!confirm("Restore this product?")) return;

    const res = await fetch(`/api/products/${id}/restore`, {
        method: "POST",
        headers: { Authorization: "Bearer " + token }
    });

    const data = await res.json();
    alert(data.message);
    $('#trashTable').DataTable().ajax.reload();
}

async function forceDeleteProduct(id) {
    if (!confirm("Delete permanently?")) return;

    const res = await fetch(`/api/products/${id}/force`, {
        method: "DELETE",
        headers: { Authorization: "Bearer " + token }
    });

    const data = await res.json();
    alert(data.message);
    $('#trashTable').DataTable().ajax.reload();
}
</script>

</body>
</html>
