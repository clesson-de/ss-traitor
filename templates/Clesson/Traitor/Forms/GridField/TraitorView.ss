<% if $Creator %>
    <div class="grid-field__col-traitor-creator">
        <% if $Creator.Link %>
            <%t Clesson\\Traitor\\Forms\\GridField\\TraitorView.CreatedBy 'Created by' name= %> <a href="{$Creator.Link}" class="name">{$Creator.Title}</a>
            <%t Clesson\\Traitor\\Forms\\GridField\\TraitorView.DateAt 'on' %> <span class="date">{$Creator.Date}</span>
        <% else_if $Creator.Title %>
            <%t Clesson\\Traitor\\Forms\\GridField\\TraitorView.CreatedBy 'Created by' %> <span class="name">{$Creator.Title}</span>
            <%t Clesson\\Traitor\\Forms\\GridField\\TraitorView.DateAt 'on' %> <span class="date">{$Creator.Date}</span>
        <% end_if %>
    </div>
<% end_if %>
<% if $Editor %>
    <div class="grid-field__col-traitor-editor">
        <% if $Editor.Link %>
            <%t Clesson\\Traitor\\Forms\\GridField\\TraitorView.LastEditedBy 'Last edited by' %> <a href="{$Editor.Link}" class="name">{$Editor.Title}</a>
            <%t Clesson\\Traitor\\Forms\\GridField\\TraitorView.DateAt 'on' %> <span class="date">{$Editor.Date}</span>
        <% else_if $Editor.Title %>
            <%t Clesson\\Traitor\\Forms\\GridField\\TraitorView.LastEditedBy 'Last edited by' %> <span class="name">{$Editor.Title}</span>
            <%t Clesson\\Traitor\\Forms\\GridField\\TraitorView.DateAt 'on' %> <span class="date">{$Editor.Date}</span>
        <% end_if %>
    </div>
<% end_if %>
