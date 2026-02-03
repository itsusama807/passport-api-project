<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Products List</h5>
            <a href="{{ route('products.trash.page') }}" class="btn btn-warning btn-sm">Trash</a>
        </div>
        <div class="card-body">
            <table id="productsTable" class="table table-bordered">
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
    loadProducts();
});

function loadProducts() {
    $('#productsTable').DataTable({
        destroy: true,
        ajax: {
            url: "/api/products",
            type: "GET",
            headers: {
                "Authorization": "Bearer " + token,
                "Accept": "application/json"
            },
            dataSrc: "products"
        },
        columns: [
            { data: "id" },
            { data: "name" },
            { data: "price" },
            {
                data: null,
                render: function (data) {
                    return `<button class="btn btn-danger btn-sm" onclick="trashProduct(${data.id})">Trash</button>`;
                }
            }
        ]
    });
}


async function trashProduct(id) {
    if (!confirm("Move this product to trash?")) return;

    try {
        const res = await fetch(`/api/products/${id}`, {
            method: "DELETE",
            headers: {
                "Authorization": "Bearer " + token,
                "Accept": "application/json"
            }
        });
        const data = await res.json();
        alert(data.message);
        $('#productsTable').DataTable().ajax.reload();
    } catch (error) {
        console.error(error);
        alert("Something went wrong!");
    }
}
</script>


</body>
</html>
