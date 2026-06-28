export function getPriorityVariant(priority) {
    switch (priority) {
        case 'High':
            return 'destructive';

        case 'Medium':
            return 'secondary';

        case 'Low':
            return 'default';

        default:
            return 'default';
    }
}

export function getPriorityLabel(priority) {
    return `${priority} Priority`;
}

export function getProgressColor(progress) {
    if (progress === 100) return 'bg-green-500';
    if (progress >= 70) return 'bg-blue-500';
    if (progress >= 40) return 'bg-yellow-500';
    if (progress > 0) return 'bg-orange-500';

    return 'bg-gray-400';
}

export function getPercentageColor(progress) {
    if (progress === 100) return 'text-green-600';
    if (progress >= 70) return 'text-blue-600';
    if (progress >= 40) return 'text-yellow-600';
    if (progress > 0) return 'text-orange-600';

    return 'text-gray-400';
}

export function getProgressStatus(progress) {
    if (progress === 0)
        return 'Not Yet Started';

    if (progress === 100)
        return 'Completed';

    return 'In Progress';
}