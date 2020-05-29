import React from 'react'
import Task from './Task'

class ToDoList extends React.Component {
  constructor() {
    super()
  }

  render() {
    const taskCompontents = this.props.tasks.map(task => (
      <li>
        <Task
          key={task.taskId}
          changeFunction={this.props.changeFunction}
          toDo={task}
        />
      </li>
    ))
    return taskCompontents.length > 0 ? (
      <ol>{taskCompontents}</ol>
    ) : (
      <p> Nothing to show! </p>
    )
  }
}

export default ToDoList