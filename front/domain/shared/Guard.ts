interface GuardResult {
    succeeded: boolean
    message?: string
}

interface GuardArgument {
    argument: any
    argumentName: string
}

export type GuardArgumentCollection = Array<GuardArgument>
export class Guard {
    public static againstNullOrUndefined (argument: any, argumentName: string): GuardResult {
        if (argument === null || argument === undefined) {
            return { succeeded: false, message: `${argumentName} is null or undefined` }
        }
        return { succeeded: true }
    }

    public static againstNullOrUndefinedBulk(args: GuardArgumentCollection): GuardResult {
        for (let arg of args) {
            const result = this.againstNullOrUndefined(arg.argument, arg.argumentName)
            if (!result.succeeded) return result
        }

        return { succeeded: true }
    }
}