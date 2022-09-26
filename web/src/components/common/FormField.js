import {Component} from "react";
import './FormField.css'

export default class FormField extends Component
{
    constructor(props) {
        super(props)
        this.state = {
            label: props.label,
            name: props.name
        }
    }

    render() {
        return (
            <div className="FormField">
                <span>{this.state.label}</span>
                <input name={this.state.name}/>
            </div>
        )
    }
}
