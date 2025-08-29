@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col">
            <h2>{{ $issue->title }}</h2>
            <div class="muted">Project: <a
                    href="{{ route('projects.show', $issue->project) }}">{{ $issue->project->name }}</a></div>
            <div class="muted">Status: {{ $issue->status }} • Priority: {{ $issue->priority }} • Due:
                {{ $issue->due_date?->toDateString() }}</div>

            <h3 class="mb-2">Tags</h3>
            <div id="tags">
                @foreach ($issue->tags as $t)
                    <span class="badge" data-tag-id="{{ $t->id }}">#{{ $t->name }}
                        <button class="btn btn-ghost" onclick="detachTag({{ $issue->id }},{{ $t->id }})">x</button>
                    </span>
                @endforeach
            </div>

            <div class="mb-3">
                <label>Shto tag</label>
                <select id="tagSelect" class="select">
                    @foreach ($allTags as $t)
                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                    @endforeach
                </select>
                <button class="btn" onclick="attachTag({{ $issue->id }})">Attach</button>
            </div>

            <h3 class="mb-2">Comments</h3>
            <form id="commentForm" class="card mb-2" onsubmit="return submitComment(event, {{ $issue->id }})">
                <div class="row">
                    <input class="input col" name="author_name" placeholder="Your name">
                </div>
                <textarea class="input mb-2" name="body" placeholder="Write a comment..."></textarea>
                <div class="error" id="commentErrors"></div>
                <button class="btn btn-primary">Send</button>
            </form>

            <div id="comments"></div>
            <div class="row"><button id="loadMoreBtn" class="btn" onclick="loadComments({{ $issue->id }})">Load
                    comments</button></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let nextCommentsUrl = null;

        async function loadComments(issueId, url = null) {
            const endpoint = url ?? (`/issues/${issueId}/comments` + (nextCommentsUrl ? nextCommentsUrl.split('?')[1] ? `?${nextCommentsUrl.split('?')[1]}` : '' : ''));
            const res = await ajax(endpoint);
            const list = document.getElementById('comments');
            res.data.forEach(c => {
                const el = document.createElement('div');
                el.className = 'card mb-2';
                el.innerHTML = `<strong>${c.author_name}</strong><div class="muted">${new Date(c.created_at).toLocaleString()}</div><div>${c.body}</div>`;
                list.appendChild(el);
            });
            nextCommentsUrl = res.meta.next_page_url;
            document.getElementById('loadMoreBtn').style.display = nextCommentsUrl ? 'inline-block' : 'none';
        }

        async function submitComment(e, issueId) {
            e.preventDefault();
            const form = document.getElementById('commentForm');
            const data = Object.fromEntries(new FormData(form).entries());
            document.getElementById('commentErrors').textContent = '';
            try {
                const res = await ajax(`/issues/${issueId}/comments`, { method: 'POST', body: JSON.stringify(data) });
                const list = document.getElementById('comments');
                const el = document.createElement('div');
                el.className = 'card mb-2';
                el.innerHTML = `<strong>${res.comment.author_name}</strong><div class="muted">${new Date(res.comment.created_at).toLocaleString()}</div><div>${res.comment.body}</div>`;
                list.prepend(el);
                form.reset();
            } catch (err) {
                try { const j = JSON.parse(err.message); if (j.errors) { document.getElementById('commentErrors').textContent = Object.values(j.errors).flat().join(' '); } }
                catch { document.getElementById('commentErrors').textContent = 'Error'; }
            }
            return false;
        }

        async function attachTag(issueId) {
            const tagId = document.getElementById('tagSelect').value;
            await ajax(`/issues/${issueId}/tags/${tagId}`, { method: 'POST' });
            const tagsDiv = document.getElementById('tags');
            const opt = document.querySelector(`#tagSelect option[value='${tagId}']`).textContent;
            const span = document.createElement('span');
            span.className = 'badge';
            span.dataset.tagId = tagId;
            span.innerHTML = `#${opt} <button class="btn btn-ghost" onclick="detachTag(${issueId},${tagId})">x</button>`;
            tagsDiv.appendChild(span);
        }

        async function detachTag(issueId, tagId) {
            await ajax(`/issues/${issueId}/tags/${tagId}`, { method: 'DELETE' });
            document.querySelector(`#tags span[data-tag-id='${tagId}']`)?.remove();
        }

        window.addEventListener('DOMContentLoaded', () => loadComments({{ $issue->id }}));
    </script>
@endpush