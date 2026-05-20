// TaskViewModel.js - O cérebro do Front-end
class TaskViewModel {
    constructor() {
        this.tasks = []; // O "Estado" da aplicação (Model no lado do cliente)
        this.init();
    }

    async init() {
        // Mapeia elementos da View
        this.listElement = document.getElementById('taskList');
        this.inputElement = document.getElementById('taskTitle');
        this.descricaoElement = document.getElementById('descricao');
        this.responsavelElement = document.getElementById('responsavel');
        this.dataVencElement = document.getElementById('dataVenc');
        this.addBtn = document.getElementById('addBtn');

        // Eventos da View que disparam ações na ViewModel
        this.addBtn.onclick = () => this.addTask();

        // Busca os dados iniciais
        await this.fetchTasks();
    }

    // Busca dados da API PHP
    async fetchTasks() {
        const response = await fetch('api.php?action=list');
        this.tasks = await response.json();
        this.render(); // Atualiza a View (Data Binding)
    }

    async addTask() {
        const title = this.inputElement.value;
        const descricao = this.descricaoElement.value;
        const responsavel = this.responsavelElement.value;
        const dataVenc = this.dataVencElement.value;

        if (!title) return alert("Digite algo!");

        await fetch('api.php?action=create', {
            method: 'POST',
            body: JSON.stringify({ title: title, descricao: descricao, responsavel: responsavel, dataVenc: dataVenc })
            //  $model->save($data['title'], $data['descricao'], $data['responsavel'], $data['dataVenc']);
        });

        this.inputElement.value = '';
        this.descricaoElement.value = '';
        this.responsavelElement.value = '';
        this.dataVencElement.value = '';
        await this.fetchTasks();
    }

    async deleteTask(id) {
        await fetch(`api.php?action=delete&id=${id}`);
        await this.fetchTasks();
    }
    async completeTask(id) {
        await fetch(`api.php?action=complete&id=${id}`);
        await this.fetchTasks();
    }

    // O "Binder": Sincroniza o array de tarefas com o HTML
    render() {
        this.listElement.innerHTML = '';
        this.tasks.forEach(task => {
            const li = document.createElement('li');
            li.className = task.done ? 'done' : '';
            li.innerHTML = `
            <div class="flex justify-between items-center border-b border-gray-200 p-2">
                <div class="flex flex-wrap gap-1">
                    <strong class="font-bold">${task.title}</strong> | Responsavel: ${task.responsavel}<br>
                    <small class="text-gray-600">${task.descricao} | Vence em: ${task.dataVenc}</small>
                </div>
                <div class="flex items-center justify-end gap-1">
                    <button onclick="vm.completeTask(${task.id})" class="border-2 border-green-500 bg-green-100 hover:bg-green-200 p-2 ml-2 rounded-full">✅</button>
                    <button onclick="vm.deleteTask(${task.id})" class="border-2 border-red-500 bg-red-100 hover:bg-red-200 p-2 ml-2 rounded-full">❌</button>
                </div>
            </div>
            `;
            this.listElement.appendChild(li);
        });
    }
}

// Instancia a ViewModel
const vm = new TaskViewModel();