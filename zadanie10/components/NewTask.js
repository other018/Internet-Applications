import React from 'react'

class NewTask extends React.Component {
  constructor() {
    super()
    this.state = {
      taskName: ""
    }

    this.changeTaskName = this.changeTaskName.bind(this)
    this.sendNewTask = this.sendNewTask.bind(this)
    this.checkKey = this.checkKey.bind(this)
  }

  changeTaskName() {
    const { value } = event.target
    this.setState({
      taskName: value
    })
  }

  checkKey() {
    const code = event.code
    if (code === "Enter" || code === "NumpadEnter") {
      this.sendNewTask()
    }
  }

  sendNewTask() {
    if (this.state.taskName === "") {
      document.getElementById("inputTaskName").focus()
      return
    }

    this.props.appAddNewTask(this.state.taskName)
    this.setState({
      taskName: ""
    })
    document.getElementById("inputTaskName").focus()
  }

  render() {
    return (
      <div className="middle">
        <input
          id="inputTaskName"
          type="text"
          value={this.state.taskName}
          onChange={this.changeTaskName}
          onKeyPress={this.checkKey}
        />
        <input type="submit" value="Add" onClick={this.sendNewTask} />
      </div>
    )
  }
}

export default NewTask