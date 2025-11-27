document.addEventListener('DOMContentLoaded', () => {
    
    const apiUrl = 'api.php';

    const userForm = document.getElementById('userForm');
    const userTableBody = document.getElementById('userTableBody');
    const userIdInput = document.getElementById('userId');
    const nomeInput = document.getElementById('nome');
    const emailInput = document.getElementById('email');
    const btnCancelar = document.getElementById('btnCancelar');

    async function fetchUsers() {
        userTableBody.innerHTML = '';
        
        try {
            const response = await fetch(`${apiUrl}?action=readAll`);
            if (!response.ok) {
                throw new Error('Erro ao buscar usuários: ' + response.statusText);
            }
            const users = await response.json();

            users.forEach(user => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${user.id}</td>
                    <td>${user.nome}</td>
                    <td>${user.email}</td>
                    <td>
                        <button class="btn-editar" data-id="${user.id}">Editar</button>
                        <button class="btn-deletar" data-id="${user.id}">Deletar</button>
                    </td>
                `;
                userTableBody.appendChild(tr);
            });
        } catch (error) {
            console.error('Erro no fetchUsers:', error);
        }
    }

    async function handleFormSubmit(event) {
        event.preventDefault(); 

        const id = userIdInput.value;
        const nome = nomeInput.value;
        const email = emailInput.value;

        const userData = { nome, email };
        let action = 'create';
        
        if (id) {
            userData.id = id;
            action = 'update';
        }

        try {
            const response = await fetch(`${apiUrl}?action=${action}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(userData),
            });

            const result = await response.json();
            alert(result.message); 
            
            resetForm();
            fetchUsers();

        } catch (error) {
            console.error('Erro ao salvar usuário:', error);
            alert('Erro ao salvar usuário.');
        }
    }

    async function handleEditClick(event) {
        if (!event.target.classList.contains('btn-editar')) return;

        const id = event.target.dataset.id;
        
        try {
            const response = await fetch(`${apiUrl}?action=readOne&id=${id}`);
            const user = await response.json();

            userIdInput.value = user.id;
            nomeInput.value = user.nome;
            emailInput.value = user.email;

            btnCancelar.classList.remove('hidden');

        } catch (error) {
            console.error('Erro ao buscar usuário para edição:', error);
        }
    }

    async function handleDeleteClick(event) {
        if (!event.target.classList.contains('btn-deletar')) return;

        const id = event.target.dataset.id;
        
        if (!confirm('Tem certeza que deseja deletar este usuário?')) {
            return;
        }

        try {
            const response = await fetch(`${apiUrl}?action=delete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: id }),
            });

            const result = await response.json();
            alert(result.message);
            
            fetchUsers(); 

        } catch (error) {
            console.error('Erro ao deletar usuário:', error);
            alert('Erro ao deletar usuário.');
        }
    }

    function resetForm() {
        userForm.reset();
        userIdInput.value = ''; 
        btnCancelar.classList.add('hidden'); 
    }


    fetchUsers();

    userForm.addEventListener('submit', handleFormSubmit);

    userTableBody.addEventListener('click', (event) => {
        handleEditClick(event);
        handleDeleteClick(event);
    });

    btnCancelar.addEventListener('click', resetForm);

});