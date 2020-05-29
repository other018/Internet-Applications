import React from 'react'
import ToDoList from './components/ToDoList'
import NewTask from './components/NewTask'
import Filter from './components/Filter'

class App extends React.Component {
  constructor() {
    super()

    this.state = {
      tasks: [],
      hide: false,
      maxId: 0
    }

    this.changeHide = this.changeHide.bind(this)
    this.addNewTask = this.addNewTask.bind(this)
    this.changeTaskDone = this.changeTaskDone.bind(this)
  }

  /*
  componentDidMount() {
    this.setState({
      tasks: [
        { taskId: 1, done: true, taskName: "Task 1" },
        { taskId: 2, done: false, taskName: "Task 2" },
        { taskId: 3, done: false, taskName: "Task 3" },
        { taskId: 4, done: true, taskName: "Task 4" },
        { taskId: 5, done: false, taskName: "Task 5" },
        { taskId: 6, done: true, taskName: "Task 6" }
      ]
    })
  }
  */

  changeHide() {
    const newHide = event.target.checked
    this.setState({
      hide: newHide
    })
  }

  addNewTask(taskName) {
    this.setState(prevState => {
      return {
        tasks: [
          ...prevState.tasks,
          { taskId: this.state.maxId, done: false, taskName: taskName }
        ],
        maxId: this.state.maxId + 1
      }
    })
  }

  changeTaskDone(id) {
    const newTasks = []
    this.setState(prevState => {
      for (const task of prevState.tasks) {
        if (task.taskId === id) {
          const newTask = {
            taskId: task.taskId,
            done: !task.done,
            taskName: task.taskName
          }
          newTasks = [...newTasks, newTask]
        } else {
          newTasks = [...newTasks, task]
        }
      }
      return {
        tasks: [...newTasks]
      }
    })
  }

  render() {
    return (
      <div>
        <h2 className="title"> Welcome to my To Do list! </h2>
        <Filter changeHide={this.changeHide} />
        <hr />
        <ToDoList
          changeFunction={this.changeTaskDone}
          tasks={
            this.state.hide
              ? this.state.tasks.filter(todo => todo.done === false)
              : this.state.tasks
          }
        />
        <hr />
        <NewTask appAddNewTask={this.addNewTask} />
      </div>
    )
  }
}

export default App