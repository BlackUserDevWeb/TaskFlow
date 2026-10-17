<form class="card form-card" method="POST" action="{{ $action }}">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif
    <div class="form-grid">
        <div class="field span-2">
            <label for="title">Titre <span aria-hidden="true">*</span></label>
            <input class="input" id="title" name="title" type="text" value="{{ old('title', $task?->title) }}" maxlength="255" required autofocus>
            @error('title')<p class="error">{{ $message }}</p>@enderror
        </div>
        <div class="field span-2">
            <label for="description">Description <span style="font-weight:400;color:var(--muted)">(facultative)</span></label>
            <textarea class="textarea" id="description" name="description" maxlength="10000" placeholder="Ajoutez du contexte ou les prochaines étapes…">{{ old('description', $task?->description) }}</textarea>
            @error('description')<p class="error">{{ $message }}</p>@enderror
        </div>
        <div class="field">
            <label for="priority">Priorité <span aria-hidden="true">*</span></label>
            <select class="select" id="priority" name="priority" required>
                @foreach ($taskPriorities as $value => $label)
                    <option value="{{ $value }}" @selected(old('priority', $task?->priority ?? 'medium') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('priority')<p class="error">{{ $message }}</p>@enderror
        </div>
        <div class="field">
            <label for="status">Statut <span aria-hidden="true">*</span></label>
            <select class="select" id="status" name="status" required>
                @foreach ($taskStatuses as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $task?->status ?? 'todo') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('status')<p class="error">{{ $message }}</p>@enderror
        </div>
        <div class="field span-2">
            <label for="due_date">Date limite <span style="font-weight:400;color:var(--muted)">(facultative)</span></label>
            <input class="input" id="due_date" name="due_date" type="date" value="{{ old('due_date', $task?->due_date?->format('Y-m-d')) }}">
            @error('due_date')<p class="error">{{ $message }}</p>@enderror
        </div>
    </div>
    <div class="form-footer">
        <a class="button secondary" href="{{ $task ? route('tasks.show', $task) : route('tasks.index') }}">Annuler</a>
        <button class="button" type="submit">{{ $task ? 'Enregistrer les modifications' : 'Créer la tâche' }}</button>
    </div>
</form>
