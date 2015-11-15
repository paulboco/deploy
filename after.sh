# Bash
echo "Setting color prompt"
echo "PS1='\[\e[1;36m\]\u@\h\[\e[1;33m\]\w\$\[\e[0m\] '" >> /home/vagrant/.profile

echo "Creating aliases"
echo "alias composer='composer --ansi'" >> /home/vagrant/.profile
echo "alias console='php console.php'" >> /home/vagrant/.profile
