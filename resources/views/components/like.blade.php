@props(['entityType', 'entityId'])

<div class="d-flex align-items-center">
    <button class="btn btn-success btn-sm me-2 like-btn" data-type="{{ $entityType }}" data-id="{{ $entityId }}" data-value="1">
        👍 Like
    </button>
    <button class="btn btn-danger btn-sm dislike-btn" data-type="{{ $entityType }}" data-id="{{ $entityId }}" data-value="-1">
        👎 Dislike
    </button>
</div>

<script>
document.querySelectorAll('.like-btn, .dislike-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
        let res = await fetch("/like", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                entity_type: btn.dataset.type,
                entity_id: btn.dataset.id,
                value: btn.dataset.value
            })
        });
        let data = await res.json();
        alert("Action: " + data.status);
    });
});
</script>
