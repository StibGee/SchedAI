import sys

# Check if the correct number of arguments is provided
if len(sys.argv) != 4:
    print("Usage: python script.py <name> <age> <city>")
    sys.exit(1)

# Retrieve the command-line arguments
name = sys.argv[1]
age = sys.argv[2]
city = sys.argv[3]

# Print the received arguments
print(f"Name: {name}, Age: {age}, City: {city}")
