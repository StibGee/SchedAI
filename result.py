import numpy as np
import scipy.stats as stats
import matplotlib.pyplot as plt
import seaborn as sns

# Data
backtracking_times = [938, 994, 963, 945, 914, 961, 982, 1012, 992, 935]
greedy_times = [345, 329, 392, 383, 385, 352, 338, 362, 374, 345]

# Descriptive statistics
mean_backtracking = np.mean(backtracking_times)
mean_greedy = np.mean(greedy_times)

std_backtracking = np.std(backtracking_times)
std_greedy = np.std(greedy_times)

# T-test
t_test_result = stats.ttest_ind(backtracking_times, greedy_times)
print(f'T-Test p-value: {t_test_result.pvalue}')

# If p-value < 0.05, there is a significant difference between the algorithms

# Visualization
# Bar chart with error bars
algorithms = ['Backtracking', 'Greedy']
means = [mean_backtracking, mean_greedy]
stds = [std_backtracking, std_greedy]

plt.figure(figsize=(10, 6))
plt.bar(algorithms, means, yerr=stds, capsize=5, color=['blue', 'green'])
plt.title('Algorithm run-time comparison')
plt.ylabel('Mean run-time (sec)')
plt.show()

# Box plot
plt.figure(figsize=(10, 6))
sns.boxplot(data=[backtracking_times, greedy_times])
plt.xticks([0, 1], ['Backtracking', 'Greedy'])
plt.title('Run-time distributions')
plt.ylabel('Run-time (sec)')
plt.show()
