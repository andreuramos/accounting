import {Component} from "react";
import "./RegisterForm.css";
import FormField from "./common/FormField";

export default class RegisterFrom extends Component
{
    render() {
        return (
            <div className="RegisterForm">
                <div className="RegisterFormHeader">Register</div>
                <FormField
                    name="username"
                    label="User Name"
                />
                <FormField
                    name="email"
                    label="Email"
                />
                <FormField
                    name="password"
                    label="Password"
                />
                <FormField
                    name="confirm_password"
                    label="Confirm Password"
                />
                <div className="RegisterFormSubmit">
                    <a className="SubmitButton">
                        Register
                    </a>
                </div>
            </div>
        )
    }
}
