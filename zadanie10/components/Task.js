import React from 'react'

class Task extends React.Component {
  constructor() {
    super()
  }

  render() {
    const { taskId, done, taskName } = this.props.toDo
    return (
      <div className={done ? "doneTask" : "noDoneTask"}>
        <input
          type="checkbox"
          checked={done}
          onChange={() => this.props.changeFunction(taskId)}
        />
        {taskName}
      </div>
    )
  }
}

export default Task