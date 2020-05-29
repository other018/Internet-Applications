import React from 'react'

class Filter extends React.Component {
  constructor() {
    super()
  }

  render() {
    return (
      <div className="middle">
        <input
          type="checkbox"
          checked={this.props.hide}
          onChange={this.props.changeHide}
        />
        hide completed
      </div>
    )
  }
}

export default Filter