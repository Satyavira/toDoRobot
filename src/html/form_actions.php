<button class="delete-button" data-toDoId="<?= $toDoList['id'] ?>" onclick="deleteToDo(<?= $toDoList['id'] ?>)">
    <!-- SVG icon for delete -->
    <svg width="35" height="35" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" id="delete">
        <path d="M8 0C3.58172 0 0 3.58172 0 8C0 12.4183 3.58172 16 8 16C12.4183 16 16 12.4183 16 8C16 3.58172 12.4183 0 8 0ZM11 10L10 11L8 9L6 11L5 10L7 8L5 6L6 5L8 7L10 5L11 6L9 8L11 10Z" fill="black" />
    </svg>
</button>